<?php

namespace console\controllers;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductBrand;
use common\modules\catalog\models\ProductPrice;
use common\modules\catalog\models\ProductRubric;
use common\modules\catalog\models\ProductTag;
use common\modules\files\models\Image;
use common\modules\files\Module;
use common\modules\news\models\Article;
use Yii;
use yii\db\Connection;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\Query;
use yii\db\StaleObjectException;
use yii\helpers\Console;
use yii\helpers\StringHelper;
use yii\image\drivers\Image as ImgDriver;
use yii\helpers\ArrayHelper as ArrayHelper;
use yii\image\drivers\Image as DriverImage;

/**
 * Действия с базой на сайте 8str.ru
 *
 * Class oldbaseController
 *
 * @package console\controllers
 *
 * @property mixed $oldDb
 */
class OldbaseController extends BaseController
{
    /** Дополнительные поля к таблицам для удобного переноса данных */
    private const TABLES_AUX_FIELDS = [
        [
            'table' => 'product_rubric',
            'column' => 'old_id',
            'type' => 'int',
        ],
        [
            'table' => 'product_rubric',
            'column' => 'old_parent_id',
            'type' => 'int',
        ],
        [
            'table' => 'product',
            'column' => 'old_id',
            'type' => 'int',
        ],
        [
            'table' => 'product',
            'column' => 'old_rubric_id',
            'type' => 'int',
        ],
        [
            'table' => 'product_brand',
            'column' => 'old_id',
            'type' => 'int',
        ],
    ];
    
    private const CHANGE_PRODUCT_FIELDS = [
        1505 => [
            'field' => 'name',
            'newValue' => 'Светодиодный светильник Ферекс ДПП 01-135-50-Г65',
        ]
    ];

    private $oldSqlDump = '../migrations/files/oldDb.sql';
    private $imagesPath = '@common/webFiles/products/images/';
    private $oldDbName = 'fbkru_0_8str';
    private $badProductsFile = '../bad_products.csv';
    private $currentPID;

    public $defaultAction = 'import';
    public $autoCreateAuxFields = true;
    public $autoDeleteAuxFields = false;
    public $importCreateBadProductsFile = false;


    public function init() {
        parent::init();
        $this->imagesPath = Yii::getAlias($this->imagesPath);
    }

    public function __destruct() {
        if ($this->currentPID) {
            /* Убиваем процесс ssh тунеля */
            $this->trace('Убиваем процесс ssh тунеля');
            shell_exec("kill -KILL $this->currentPID");
        }
    }

    /**
     * @inheritdoc
     */
    public function options($actionID) {
        return array_merge(parent::options($actionID), [
            'autoCreateAuxFields', // Автоматически создавать вспомогательные поля в базе если их нет
            'autoDeleteAuxFields', // Автоматически удалять вспомогательные поля. Если удалить, то при следующем копировании содержимое базы удалится
            'importCreateBadProductsFile', // При импортировании создать файл с плохими и задвоенными продуктами
        ]);
    }

    /**
     * @inheritdoc
     * @since 2.0.8
     */
    public function optionAliases() {
        return array_merge(parent::optionAliases(), [
            'a' => 'autoCreateAuxFields',
            'd' => 'autoDeleteAuxFields',
            'f' => 'importCreateBadProductsFile',
        ]);
    }

    /**
     * Создать вспомогательные поля для импорта старой базы
     *
     * @return int
     * @throws Exception
     */
    public function actionCreateAuxFields() {
        return $this->createAuxFields();
    }

    /**
     * Удалить вспомогательные поля из базы
     *
     * @return int
     */
    public function actionDeleteAuxFields() {
        return $this->deleteAuxFields();
    }
    
    /**
     * Копирует удаленную базу с сайта 8str.ru в локальный дамп.
     * Необходим файл с настройками доступа к удаленному серверу db2.php
     *
     * @return int
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDumpRemoteBase() {
        chdir(dirname(__FILE__));
        if (file_exists('db2.php')) {
            $config = [];
            $db = require 'db2.php';
            if (is_int($db)) {
                return $db;
            }
            $db->close();
            $command = "mysqldump -h127.0.0.1 -P3307 -u{$config['username']} -p{$config['password']} fbkru_0_8str > $this->oldSqlDump";
            return shell_exec($command);
        } elseif ($db = Yii::$app->get('old_db', false)) {
            $config['username'] = $db->username;
            $config['password'] = $db->password;

            $command = "mysqldump -u{$config['username']} -p{$config['password']} fbkru_0_8str > $this->oldSqlDump";
            return shell_exec($command);
        } else {
            $this->error('Необходим файл настройки db2.php. За подробностями обратиться к разработчику');
            return 1;
        }
    }

    /**
     * Migrate old database dump
     * @return int
     */
    public function actionMigrateOldDbDump() {
        chdir(dirname(__FILE__));
        try {
            $databases = yii::$app->db->createCommand('SHOW DATABASES')->queryColumn();
            if (in_array($this->oldDbName, $databases)) {
                yii::$app->db->createCommand("DROP DATABASE `$this->oldDbName`")->execute();
            }
            yii::$app->db->createCommand("CREATE DATABASE `$this->oldDbName`")->execute();
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        shell_exec("mysql -h localhost -u{$this->module->db->username} -p{$this->module->db->password} {$this->oldDbName} < $this->oldSqlDump");

        return 0;
    }
    
    /**
     * Get connection to the old database
     *
     * @return Connection
     * @throws \yii\base\InvalidConfigException
     */
    private function getOldDb() {
        /** @var Connection $remoteDb */
        $remoteDb = Yii::$app->get('old_db', false);
        if (!$remoteDb) {
            $remoteDb = clone yii::$app->db;
            $remoteDb->dsn = preg_replace(
                '#dbname=[^;]+#',
                "dbname={$this->oldDbName}",
                yii::$app->db->dsn
            );

            $remoteDb->tablePrefix = 'pdx';
        }

        return $remoteDb;
    }

    /**
     * Импортирует данные из удаленной базы
     *
     * @return int
     * @throws Exception
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionImport() {
        $remoteDb = $this->getOldDb();

        try {
            $remoteDb->open();
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        /* Добавляем вспомагательные поля в базу (при необходимости)*/
        if (!$this->checkAuxFields()) {
            if (!$this->autoCreateAuxFields) {
                $this->error('Перед импортом базы необходимо создать вспомогательные поля!');
                return 1;
            }
            if ($err = $this->createAuxFields()) {
                return $err;
            }
        }


        /* Экспортируем рубрикаторы */
        if ($err = $this->exportRubrics($remoteDb)) {
            return $err;
        }

        /* Экспортируем продукты */
        if ($err = $this->exportProducts($remoteDb)) {
            return $err;
        }

        /* Удаляем вспомогательные поля из базы */
        if ($this->autoDeleteAuxFields) {
            if ($err = $this->deleteAuxFields()) {
                return $err;
            }
        }

        $this->success('База успешно экспортирована');
        return 0;
    }

    /**
     * Импортирует продукты
     * @param $remoteDb
     * @return int
     * @throws Exception
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function exportProducts($remoteDb) {
        /** @var \common\modules\catalog\Module $catalog */
        $catalog = Yii::$app->getModule('catalog');

        $this->trace('Экспорт Продутов');

        if ($this->importCreateBadProductsFile) {
            $badProducts = [
                'Тип ошибки' => '',
                'Номер товара' => '',
                'Название продукта' => '',
                'Номер дубля' => '',
                'Название дубля' => '',
            ];

            $badProdFile = fopen(dirname(__DIR__) . '/runtime/' . $this->badProductsFile, 'w');
            fputcsv($badProdFile, array_keys($badProducts), ';');
            $badProducts = [];
        }

        $stActive = Product::STATUS['ACTIVE'];
        $stHidden = Product::STATUS['HIDDEN'];
        /* Читаме продукты */
        $query = new Query();
        $query->select([
            '`n`.`nid` as `old_id`',
            "if (`cat2`.`tid`, {$stActive}, {$stHidden}) as `status`",
            '`n`.`title` as `name`',
            'CONCAT (`bod`.`body_value`, `bod`.`body_summary`) as `desc`',
            '`prod`.`sell_price` as `8str_price`',
            '`vsp`.`field__price_vigsec_value` as `vigsec_price`',
            '`n`.`promote` as `show_on_home`',
            '`n`.`sticky` as `on_list_top`',
            'REPLACE(`f`.`uri`, \'public://product/\', \'\') as `filename`',
            '`m`.`field_market_value` as `market_upload`',
            '`hit`.`field_hit_value` as `bestseller`',
            '`a`.`field_action_value` as `is_promo`',
            '`ne`.`field_new_value` as `is_new`',
            '`man2`.`tid` as `old_brand_id`',
            '`man2`.`name` as `brand_name`',
            '`man2`.`description` as `brand_desc`',
            '`cat2`.`tid` as `old_rubric_id`',
            '`cat2`.`name` as `rubric_name`',
            'if (`md`.`field_market_days_value` = 0, 1, 0) as count',
            '`md`.`field_market_days_value` as delivery_days',
        ])->from('{{%node}} as `n`')
            ->leftJoin('{{%field_data_uc_product_image}} as `im`', '`n`.`nid` = `im`.`entity_id`')
            ->leftJoin('{{%file_managed}} as `f`', '`im`.`uc_product_image_fid` = `f`.`fid`')
            ->leftJoin('{{%uc_products}} as `prod`', '`prod`.`nid` = `n`.`nid`')
            ->leftJoin('{{%field_data_field_market}} as `m`', '`m`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%field_data_field_hit}} as `hit`', '`hit`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%field_data_field_action}} as `a`', '`a`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%field_data_field_new}} as `ne`', '`ne`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%field_data_field_market_days}} as `md`', '`md`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%field_data_field__price_vigsec}} as `vsp`', '`vsp`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%field_data_body}} as `bod`', '`bod`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%field_data_field_manufacturer}} as `man`', '`man`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%taxonomy_term_data}} as `man2`', '`man`.`field_manufacturer_tid` = `man2`.`tid`')
            ->leftJoin('{{%field_data_taxonomy_catalog}} as `cat`', '`cat`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%taxonomy_term_data}} as `cat2`', '`cat`.`taxonomy_catalog_tid` = `cat2`.`tid`')
            ->andWhere(['`n`.`type`' => 'product'])
            ->andWhere(['`n`.`status`' => 1])
            ->andWhere(['IS NOT', '`f`.`uri`', null])
            ->orderBy(['`n`.`changed`' => SORT_DESC]);

        $rootRubric = ProductRubric::treeFind()->where(['name' => 'Каталог', 'level' => '0'])->one();
        $rootRubricId = $rootRubric->id;
        unset($rootRubric);
        $rubrics = ProductRubric::find()
            ->select(['id', 'old_id'])
            ->indexBy('old_id')->asArray()->all();
        
        $localProducts = Product::find()
            ->select(['id', 'old_id', 'name'])
            ->active()
            ->where('[[old_id]]')
            ->indexBy('name')->asArray()->all();

        $localBrands = ProductBrand::find()
            ->indexBy('old_id')->all();

        /** @var Module $filesManager */
        $filesManager = Yii::$app->getModule('files');
        
        /** @var Image $image */
        $image = $filesManager->getEntityInstance('products/images');

        $path = \Yii::getAlias($this->imagesPath . '/newImages');
        if (file_exists($path)) {
            if (!is_dir($path)) {
                $this->error('The path is wrong.');
                return 0;
            }
        } else {
            mkdir($path, 0755, true);
        }
        $CatalogController = new CatalogController('catalog', $this->module);
        $groups = $CatalogController->findImages($path);

        $domains = yii::$app->params['domains'];
        $mainDomain = '8str';

        foreach ($query->each(100, $remoteDb) as $src) {
            $src['delivery_time'] = preg_replace('#.*(\d+)$#', '$1', $src['delivery_days']);
            $src['delivery_time'] or $src['delivery_time'] = 0;
            /* Попытка исправить найденные товары с ошибками */
            if (isset(self::CHANGE_PRODUCT_FIELDS[$src['old_id']])) {
                $ch = self::CHANGE_PRODUCT_FIELDS[$src['old_id']];
                $src[$ch['field']] = $ch['newValue'];
            }
            /* Исправляем не правильный дефис и удаляем пробелы по краям */
            $src['name'] = trim(preg_replace('#‑#m', '-', $src['name']));
            $src['market_upload'] = intval($src['market_upload']);
            /** Проверяем на наличие продукта */
            $productIsExists = isset($localProducts[$src['name']]);
            if ($productIsExists) {
                if ($localProducts[$src['name']]['old_id'] != $src['old_id']) {
                    $this->notice("Продукт {$src['old_id']}:{$src['name']} пропущен. Такой уже есть.");
                    if ($this->importCreateBadProductsFile) {
                        $badProducts = [
                            'Тип ошибки' => 'Дубль товара',
                            'Номер товара' => $src['old_id'],
                            'Название продукта' => $src['name'],
                            'Номер дубля' => $localProducts[$src['name']]['old_id'],
                            'Название дубля' => $src['name'],
                        ];
                    }
                    continue;
                } elseif(empty($localProducts[$src['name']]['processed'])) {
                    /** Обновляем продукт */
                    $product = Product::findOne($localProducts[$src['name']]['id']);
                    $product->scenario = 'oldbase';
                    if (empty($localProducts[$src['name']]['processed'])) {
                        $product->setAttributes($src);
                        /** Если есть рубрика для продукта, то присоединяем продукт к ней */
                        if (!empty($src['old_rubric_id']) && isset($rubrics[$src['old_rubric_id']])) {
                            $product->main_rubric_id = $rubrics[$src['old_rubric_id']]['id'];
                        } else {
                            $product->main_rubric_id = $rootRubricId;
                        }
                        if (!$product->save()) {
                            $this->error("Продукт {$src['old_id']}:{$src['name']} не обновлен", $product->errors);
                            continue;
                        }
                    }
                    $localProducts[$src['name']]['processed'] = true;
                } elseif (!isset($product) || !is_object($product) || $product->id !== $localProducts[$src['name']]['id']) {
                    $product = Product::findOne($localProducts[$src['name']]['id']);
                }
            } else {
                $transaction = Yii::$app->db->beginTransaction();
                /** Если продукта нет, то и брэнда (производителя) может не быть */
                if ($src['old_brand_id']) {
                    if(empty($localBrands[$src['old_brand_id']])) {
                        /** @noinspection MissedFieldInspection */
                        $brand = new ProductBrand([
                            'name' => $src['brand_name'],
                            'desc' => $src['brand_desc'],
                            'old_id' => $src['old_brand_id'],
                        ]);
                        if (!$brand->save()) {
                            $this->error("Брэнд {$src['old_brand_id']}:{$src['brand_name']} не сохранен", $brand->errors);
                            $transaction->rollBack();
                            return 1;
                        }
                        $localBrands[$src['old_brand_id']] = $brand;
                    } else {
                        $brand = $localBrands[$src['old_brand_id']];
                    }
                } else {
                    $brand = null;
                }

                /** Создаем продукт */
                /** @var Product $product */
                $product = new Product(['scenario' => 'oldbase']);
                $product->setAttributes($src);
                $product->brand_id = $brand ? $brand->id : null;

                if (!$product->save()) {
                    $this->error("Продукт {$src['old_id']}:{$src['name']} не сохранен", $product->errors);
                    $transaction->rollBack();
                    continue;
                }

                /** Если есть рубрика для продукта, то присоединяем продукт к ней */
                if (!empty($src['old_rubric_id']) && isset($rubrics[$src['old_rubric_id']])) {
                    $product->main_rubric_id = $rubrics[$src['old_rubric_id']]['id'];
                    $product->save();
                } else {
                    $product->main_rubric_id = $rootRubricId;
                    $product->save();
                    $this->error(
                        "Не импортирована рубрика {$src['rubric_name']} для товара {$src['old_id']}:{$src['name']}. Продукт в корневой рубрике"
                    );
                    if ($this->importCreateBadProductsFile) {
                        $badProducts = [
                            'Тип ошибки' => 'Продукт без рубрики',
                            'Номер товара' => $src['old_id'],
                            'Название продукта' => $src['name'],
                            'Номер дубля' => '',
                            'Название дубля' => '',
                        ];
                    }
                }
                $transaction->commit();
                $localProducts[$src['name']] = [
                    'old_id' => $src['old_id'],
                    'name' => $src['name'],
                    'id' => $product->id,
                    'processed' => true,
                ];
            }
            
            /** Назначаем цены, если они не были назначины */
            foreach (array_keys($domains) as $domain) {
                $field = "{$domain}_price";
                $priceValue = isset($src[$field]) ? $src[$field] : $src["{$mainDomain}_price"];
                $priceValue = round((float)$priceValue, 2);

                $priceParams = [
                    'value' => $priceValue,
                    'product_id' => $product->id,
                    'domain_name' => $domain,
                ];
                if (!ProductPrice::find()->onlyActive()->andWhere($priceParams)->one()) {
                    if (!$catalog->insertNewPrice($product, $priceValue, $domain)) {
                        $this->error(
                            "Цена для товара {$src['old_id']}:{$src['name']} и домена $domain не установлена",
                            $product->errors
                        );
                        continue;
                    }
                }
            }

            $tags = [
                'new' => 'is_new',
                'bestseller' => 'bestseller',
                'promo' => 'is_promo',
            ];
            /** Если продукт имеет метку, то добавляем ее к нему */
            foreach ($tags as $tag => $srcTag) {
                if (empty($src[$srcTag]) || $product->hasTag($tag))
                    continue;
                $tag = $this->getTag($tag);
                try {
                    $product->link('tags', $tag);
                } catch(\Exception $e) {
                    $this->error(
                        "Ошибка при добавлении к товару {$product->id}:{$product->name} метки {$tag->name}",
                        $e->getMessage()
                    );
                    continue;
                }
            }
            
            /** Добавляем картинки */
            $productName = preg_replace('#[/]#', '', $product->name);
            if (array_key_exists($productName, $groups) && empty($localProducts[$src['name']]['new_images_processed'])) {
                foreach($groups[$productName] as $fileInfo) {
                    $product->addFile($fileInfo['basename']);
    
                    $image->fileName = $fileInfo['basename'];
                    $image->pickFrom($fileInfo['dirname']) and $image->adaptSize(DriverImage::CROP);
                    if ($image->exists()) {
                        $image->createThumbs();
                    }
                }
                $localProducts[$src['name']]['new_images_processed'] = true;
            }

            if (false === strpos($src['filename'], 'sertifikat')) {
                /** Добавляем файлы */
                $filePath = $filesManager->getFilePath(
                    'products/images',
                    'old/' . $src['filename'],
                    false,
                    false,
                    true
                ) or $filePath = "https://8str.ru/sites/default/files/product/{$src['filename']}";
                if ($filePath) {
                    try {
                        $image = $filesManager->createEntity('products/images', $src['filename']);
                        if ($image->pickFrom(dirname($filePath))) {
                            $newName = preg_replace('#\.\w{3,4}$#', '.png', basename($filePath));
                            /** @var Image $image */
                            $image->adaptSize(ImgDriver::ADAPT, $newName);
                            $image->createThumbs();
                            $product->addFile($image->getBasename());
                        }
                    } catch (\Exception $e) {
                        $this->error(
                            "Картинка {$src['filename']} для продукта {$src['old_id']}:{$src['name']} не добавлена",
                            $e->getMessage()
                        );
                    }
                }
            }
            
            try {
                $product->update();
            } catch(StaleObjectException $e) {
                $this->error($e->getMessage());
                $product->refresh();
            } catch(\Exception $e) {
                $this->error($e->getMessage());
                $product->refresh();
            } catch(\Throwable $e) {
                $this->error($e->getMessage());
                $product->refresh();
            }
    
            $msg = $this->ansiFormat('.', Console::FG_GREEN);
            $this->stdout($msg);

            if ($this->importCreateBadProductsFile && !empty($badProducts)) {
                /** @noinspection PhpUndefinedVariableInspection */
                fputcsv($badProdFile, $badProducts, ';');
                $badProducts = [];
            }
        }
        if ($this->importCreateBadProductsFile) {
            /** @noinspection PhpUndefinedVariableInspection */
            fclose($badProdFile);
        }
        
        $deletedProducts = [];
        foreach ($localProducts as $product) {
            if (empty($product['processed'])) {
                $deletedProducts[] = $product['id'];
            }
        }
        if (!empty($deletedProducts)) {
            Product::updateAll(['status' => Product::STATUS['DELETED']], ['id'=>$deletedProducts]);
        }
        
        return 0;
    }

    /**
     * @param string $name
     * @return ProductTag
     */
    protected function getTag(string $name): ProductTag {
        static $ret;
        if (!$ret) {
            $ret = ProductTag::find()->indexBy('name')->all();
        }

        return $ret[$name];
    }
    
    /** Новая структура рубрикаторов */
    private const RUBRICS_ORDER = [
        1 => NULL, 13 => 1, 230 => 1, 231 => 230, 232 => 230, 200 => 1, 238 => 200, 236 => 200, 237 => 200, 20 => 1,
        19 => 1, 73 => 1, 60 => 1, 74 => 1, 53 => 1, 54 => 1, 24 => 1, 225 => 1, 55 => 1,
        -1 => [
            'rubric_name' => 'Видеонаблюдение',
            'rubric_id' => 10000,
        ],
        26 => 10000, 239 => 26, 4 => 26, 216 => 4, 217 => 4, 148 => 4, 218 => 4, 147 => 4, 51 => 26, 155 => 26, 235 => 155,
        157 => 26, 102 => 10000, 198 => 102, 196 => 102, 197 => 102, 3 => 10000, 96 => 3, 80 => 3, 62 => 3, 2 => 3,
        5 => NULL, 122 => 5, 112 => 5, 114 => 5, 111 => 5, 224 => 5, 110 => 5, 113 => 5, 115 => 5, 109 => 5, 123 => 5,
        52 => NULL, 12 => NULL, 107 => 12, 118 => 12, 108 => 12, 15 => NULL, 79 => NULL, 168 => 79, 170 => 79,
        206 => 79, 169 => 79, 25 => NULL, 57 => 25, 56 => 25, 18 => NULL,
    ];

    /**
     * Export rubrics
     * @param Connection $remoteDb
     * @return int
     */
    protected function exportRubrics($remoteDb) {
        $this->trace('Экспорт рубрикатора');

        if (!$rootRubric = ProductRubric::treeFind()->where(['name' => 'Каталог', 'level' => '0'])->one()) {
            $rootRubric = new ProductRubric([
                'name' => 'Каталог'
            ]);
            $rootRubric->makeRoot();
        }
    
    
        /* Читаем рубрикатор */
        $query = new Query();
        $query->select('[[c]].[[tid]] as [[rubric_id]]')
            ->addSelect('[[c]].[[name]] as [[rubric_name]]')
            ->addSelect('[[p]].[[tid]] as [[parent_rubric_id]]')
            ->addSelect('[[p]].[[name]] as [[parent_rubric_name]]')
            ->from('{{%taxonomy_term_data}} as {{c}}')
            ->leftJoin('{{%taxonomy_term_hierarchy}} as {{h}}', '{{c}}.[[tid]] = {{h}}.[[tid]]')
            ->leftJoin('{{%taxonomy_term_data}} as {{p}}', '{{h}}.[[parent]] = {{p}}.[[tid]]')
            ->where(['{{c}}.[[vid]]' => 2])->orderBy('{{p}}.[[tid]]')
            ->indexBy('rubric_id');

        $batch = $query->batch(100, $remoteDb);
        $rubricsPull = [];
        $createdRubrics = ProductRubric::find()->select(['id', 'old_id'])->indexBy('old_id')->asArray()->all();
        $createdRubrics = ArrayHelper::map($createdRubrics, 'old_id', 'id');
        
        $rubricsOrder = self::RUBRICS_ORDER;
        do {
            /** Есил Пулл рубрик пуст или в нем остались только азвисимые рубрики, чьи зависимости не удовлетворены */
            if (empty($rubricsPull) || !empty(reset($rubricsPull)['depends_on'])) {
                // Читаем новую партию старых рубрик
                $batch->next();
                // Пулл зависимых рубрик оставляем в конце
                $rubricsPull = ArrayHelper::merge($batch->current(), $rubricsPull);
            }

            /** Если текущая в очереди новой структуры рубрика имеет отрицательный индекс */
            if ((int)key($rubricsOrder) < 0) {
                /** Значит это рубрика которую нужно создать искусственно */
                $currentRubric = reset($rubricsOrder);
                unset($rubricsOrder[key($rubricsOrder)]);
                $rubricsOrder = ArrayHelper::merge([$currentRubric['rubric_id'] => null], $rubricsOrder);
            } else {
                /** Иначе берем текущую импортируемую рубрику */
                $currentRubric = reset($rubricsPull);
                unset($rubricsPull[key($rubricsPull)]);
            }
            /** Проверяем что импорируемая рубрика предусмотрена в новой структуре рубрик */
            if (array_key_exists($currentRubric['rubric_id'], $rubricsOrder)) {
                /* Если рубрика в списке сортировки, но еще не пришла ее очередь, отложим ее */
                if ($currentRubric['rubric_id'] != key($rubricsOrder)) {
                    $rubricsPull[$currentRubric['rubric_id']] = $currentRubric;
                    continue;
                }
                /** Зависимости бирем из новой структуры рубрик */
                $parentRubricId = $rubricsOrder[$currentRubric['rubric_id']];
                unset($rubricsOrder[key($rubricsOrder)]);
            } else {
                /** Иначе берем зависимость из старой базы */
                $parentRubricId = $currentRubric['parent_rubric_id'];
            }
            /* Если у рубрики есть родитель */
            if (!empty($parentRubricId)) {
                /* Если родитель еще не записан в базу, тогда отложим рубрику пока родитель не будет записан. */
                if (!isset($createdRubrics[$parentRubricId])) {
                    $currentRubric['depends_on'] = $parentRubricId;
                    array_push($rubricsPull, $currentRubric);
                    continue;
                }

                /* Если текущая рубрика еще не записана в базу */
                if (!$newRubric = ProductRubric::findOne(['old_id' => $currentRubric['rubric_id']])) {
                    /* Если родительская рубрика есть в массиве записанных, но отсутствует в базе */
                    if (!$parentRubric = ProductRubric::findOne(['old_id' => $parentRubricId])) {
                        $this->error('Непредвиденная ошибка. Невозможно найти родительскую рубрику old_id=' .
                            $parentRubricId);
                        return 1;
                    }
                    
                    /** @noinspection MissedFieldInspection */
                    $newRubric = new ProductRubric([
                        'name' => $currentRubric['rubric_name'],
                        'old_id' => $currentRubric['rubric_id'],
                        'old_parent_id' => $parentRubricId,
                    ]);
    
                    $newRubric->appendTo($parentRubric);
                } else {
                    /** Иначе, обновим рубрику */
                    $this->notice("Обновление рубрики {$currentRubric['rubric_name']}");
                    $newRubric->name = $currentRubric['rubric_name'];
                    $newRubric->old_id = $currentRubric['rubric_id'];
                    $newRubric->old_parent_id = $parentRubricId;
                    if (!$newRubric->save()) {
                        $this->error("Ошибка обновления рубрики {{$currentRubric['rubric_name']}}", $newRubric->getErrorSummary(TRUE));
                    }
                }
                /* Если у рубрики нет родителя и эта рубрика еще не записана в базу */
            } elseif (!$newRubric = ProductRubric::findOne(['old_id' => $currentRubric['rubric_id']])) {
                /** @noinspection MissedFieldInspection */
                $newRubric = new ProductRubric([
                    'old_id' => $currentRubric['rubric_id'],
                    'name' => $currentRubric['rubric_name']
                ]);
                $newRubric->appendTo($rootRubric);
            } elseif ($newRubric) {
                /** Иначе, обновим рубрику */
                $newRubric->name = $currentRubric['rubric_name'];
                $newRubric->old_id = $currentRubric['rubric_id'];
                $newRubric->old_parent_id = $parentRubricId;
                if (!$newRubric->save()) {
                    $this->error("Ошибка обновления рубрики {{$currentRubric['rubric_name']}}", $newRubric->getErrorSummary(TRUE));
                }
            }

            $createdRubrics[$newRubric->getAttribute('old_id')] = $newRubric->id;
        } while (!empty($rubricsPull));

        $this->success('Экспорт рубрикатора успешно завершон');
        return 0;
    }

    /**
     * @return int
     * @throws Exception
     */
    protected function createAuxFields() {
        $this->trace('Добавляем вспомогательные поля в базу');
        $error = false;

        if (!$this->checkAuxFields()) {
            try {
                foreach (self::TABLES_AUX_FIELDS as $table) {
                    $params = [];
                    $query = Yii::$app->db->queryBuilder->delete("{{%{$table['table']}}}", '', $params);
                    Yii::$app->db->createCommand($query)->execute();
                    Yii::$app->db->createCommand("ALTER TABLE {{%{$table['table']}}} AUTO_INCREMENT=0")->execute();
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
            if (!$error) {
                foreach (self::TABLES_AUX_FIELDS as $tableData) {
                    if (!Yii::$app->db->schema
                        ->getTableSchema("{{%{$tableData['table']}}}")
                        ->getColumn($tableData['column'])) {
                        $query = Yii::$app->db->queryBuilder->addColumn(
                            "{{%{$tableData['table']}}}",
                            "[[{$tableData['column']}]]", $tableData['type']);
                        try {
                            Yii::$app->db->createCommand($query)->execute();
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                            break;
                        }
                    }
                }
                Yii::$app->db->close();
                Yii::$app->db->open();
            }
        } else {
            $this->trace('Дополнительные поля уже созданы');
        }

        if ($error) {
            $this->error('При добавлении вспомогательных полей, что-то пошло не так: ' . $error . "\n");
            return 1;
        } else {
            $this->success('Поля успешно добавлены в базу');
        }
        return 0;
    }

    /**
     * Delete AUX fields
     * @return int
     */
    protected function deleteAuxFields() {
        $this->trace('Удаляем вспомогательные поля из базы');
        $error = false;
        foreach (self::TABLES_AUX_FIELDS as $tableData) {
            if (Yii::$app->db->schema
                ->getTableSchema("{{%{$tableData['table']}}}")
                ->getColumn($tableData['column'])) {
                $query = Yii::$app->db->queryBuilder->dropColumn(
                    "{{%{$tableData['table']}}}",
                    "[[{$tableData['column']}]]");
                try {
                    Yii::$app->db->createCommand($query)->execute();
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    break;
                }
            }
        }

        if ($error) {
            $this->error('При удалении вспомогательных полей, что-то пошло не так: ' . $error . "\n");
            return 1;
        } else {
            $this->success('Поля успешно удалены из базы');
        }
        return 0;
    }

    /**
     * Проверка на вспомогательные поля в таблицах
     *
     * @return bool
     */
    private function checkAuxFields() {
        $ret = true;
        foreach (self::TABLES_AUX_FIELDS as $tableData) {
            if (!Yii::$app->db->schema
                ->getTableSchema("{{%{$tableData['table']}}}")
                ->getColumn($tableData['column'])) {

                $ret = false;
                break;
            }
        }

        return $ret;
    }

    /**
     * Trace
     * @param $msg
     */
    protected function trace($msg) {
        $msg = $this->ansiFormat($msg, Console::FG_YELLOW);
        $this->stdout($msg . "\n");
    }
    
    /**
     * Export news
     *
     * @throws \yii\base\ErrorException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionExportNews() {
        $oldDb = $this->getOldDb();
        $query = new Query();

        $newsRows = $query->select('node.title as title,' .
            ' from_unixtime(node.created) as created_at,' .
            ' body.body_value as fulltext,' .
            ' files.filename as image,' .
            ' files.uri as image_uri,' .
            ' node.nid as external_id')
            ->addSelect(new Expression('1 as creator_id'))
            ->addSelect(new Expression('1 as modifier_id'))
            ->from('{{%node}} node')
            ->leftJoin('{{%field_data_body}} body', 'body.entity_id=node.nid')
            ->leftJoin('{{%field_data_field_image}} image', 'image.entity_id=node.nid')
            ->leftJoin('{{%file_managed}} files', 'files.fid= image.field_image_fid')
            ->where("node.type='news' AND node.status=1 AND files.uri is not null")
            ->all($oldDb);

        $this->success('Found ' . count($newsRows) . ' articles of news.' . PHP_EOL);

        if ($newsRows) {
            /** @var Module $filesManager */
            $filesManager = Yii::$app->getModule('files');
            /** @var Image $image */
            $mainImage = $filesManager->getEntityInstance('news/images');
            foreach ($newsRows as $newsRow) {
                $image = clone $mainImage;

                $newsRow['introtext'] = StringHelper::truncate(html_entity_decode(strip_tags($newsRow['fulltext'])), 120);
                $newsRow['published_at'] = $newsRow['created_at'];

                // Prepare an image uri
                if (empty($newsRow['image_uri'])) {
                    $imageUri = '';
                } else {
                    $imageUri = str_replace('public://', '', $newsRow['image_uri']);
                }

                unset($newsRow['image_uri']);

                // Update a article data
                $article = Article::find()->where(['external_id' => $newsRow['external_id']])->one();
                if (!$article) {
                    $article = new Article();
                }

                $article->load($newsRow, '');
                if (!$article->save()) {
                    $this->error('Failed to save article.' . PHP_EOL, $article->getErrors());
                    continue;
                }

                // Download an image
                if ('' !== $imageUri) {
                    $imageName = basename($imageUri);
                    $image->fileName = $imageName;
                    $image->delete();
                    $image->pickFromRemote('http://8str.ru/sites/default/files/styles/original/public/' . $imageUri);
                    if ($image->hasErrors()) {
                        $this->error('Could not get image.' . PHP_EOL, $image->getErrors());
                    }

                    $image->createThumbs();

                    if ($image->hasErrors()) {
                        $this->error('Could not create thumbs.' . PHP_EOL, $image->getErrors());
                    }
                }
            }
        }

        $this->success('Exports are complete.' . PHP_EOL);
    }
}
