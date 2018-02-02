<?php

namespace console\controllers;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\Product2ProductRubric;
use common\modules\catalog\models\ProductBrand;
use common\modules\catalog\models\ProductPrice;
use common\modules\catalog\models\ProductRubric;
use common\modules\catalog\models\ProductTag;
use common\modules\catalog\models\ProductTag2product;
use Yii;
use yii\console\Controller;
use yii\db\Connection;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\Console;

/**
 * Действия с базой на сайте 8str.ru
 *
 * Class oldbaseController
 *
 * @package console\controllers
 *
 * @property mixed $oldDb
 */
class OldbaseController extends Controller
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
    private $imagesPath = '@common/protected/webFiles/catalog/images/';
    private $oldDbName = 'fbkru_0_8str';
    private $badProductsFile = __DIR__ . 'bad_products.csv';
    private $currentPID;
    
    public $defaultAction = 'import';
    public $autoCreateAuxFields = true;
    public $autoDeleteAuxFields = false;
    public $importCreateBadProductsFile = false;
    

    public function init(){
        parent::init();
        $this->imagesPath = Yii::getAlias($this->imagesPath);
    }
    
    public function __destruct(){
        if ($this->currentPID) {
            /* Убиваем процесс ssh тунеля */
            $this->trace('Убиваем процесс ssh тунеля');
            shell_exec("kill -KILL $this->currentPID");
        }
    }
    
    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
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
    public function optionAliases()
    {
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
        } else {
            $this->error('Необходим файл настройки db2.php. За подробностями обратиться к разработчику');
            return 1;
        }
    }
    
    public function actionMigrateOldDbDump() {
        chdir(dirname(__FILE__));
        try {
            $databases = yii::$app->db->createCommand('SHOW DATABASES')->queryColumn();
            if (in_array($this->oldDbName, $databases)) {
                yii::$app->db->createCommand("DROP DATABASE `$this->oldDbName`")->execute();
            }
            yii::$app->db->createCommand("CREATE DATABASE `$this->oldDbName`")->execute();
        } catch(Exception $e){
            $this->error($e->getMessage());
            return 1;
        }
        
        shell_exec("mysql -h localhost -u{$this->module->db->username} -p{$this->module->db->password} {$this->oldDbName} < $this->oldSqlDump");
        
        return 0;
    }

    /**
     * Импортирует данные из удаленной базы
     *
     * @return int
     * @throws Exception
     */
    public function actionImport()
    {
        if (isset(yii::$app->old_db)) {
            $remoteDb = yii::$app->old_db;
        } else {
            $remoteDb = clone yii::$app->db;
            $remoteDb->dsn = preg_replace(
                '#dbname=[^;]+#',
                "dbname={$this->oldDbName}",
                yii::$app->db->dsn
            );
            $remoteDb->tablePrefix = 'pdx';
        }
        
        try {
            $remoteDb->open();
        } catch(Exception $e) {
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
        if ($err = $this->exportRubrics($remoteDb)){
            return $err;
        }
        
        /* Экспортируем продукты */
        if ($err = $this->exportProducts($remoteDb)) {
            return $err;
        }
        
        /* Удаляем вспомогательные поля из базы */
        if ($this->autoDeleteAuxFields) {
            if ($err = $this->deleteAuxFields()){
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
     */
    protected function exportProducts($remoteDb) {
        
        $this->trace('Экспорт Продутов');
        
        if ($this->importCreateBadProductsFile) {
            $badProducts = [
                'Тип ошибки' => '',
                'Номер товара' => '',
                'Название продукта' => '',
                'Номер дубля' => '',
                'Название дубля' => '',
            ];
            
            $badProdFile = fopen($this->badProductsFile, 'w');
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
            '`prod`.`sell_price` as `price`',
            '`vsp`.`field__price_vigsec_value` as `vig_sec_price`',
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
            '`cat2`.`name` as `rubric_name`'
        ])->from('{{%node}} as `n`')
            ->leftJoin('{{%field_data_uc_product_image}} as `im`', '`n`.`nid` = `im`.`entity_id`')
            ->leftJoin('{{%file_managed}} as `f`', '`im`.`uc_product_image_fid` = `f`.`fid`')
            ->leftJoin('{{%uc_products}} as `prod`', '`prod`.`nid` = `n`.`nid`')
            ->leftJoin('{{%field_data_field_market}} as `m`', '`m`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%field_data_field_hit}} as `hit`', '`hit`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%field_data_field_action}} as `a`', '`a`.`entity_id` = `n`.`nid`')
            ->leftJoin('{{%field_data_field_new}} as `ne`', '`ne`.`entity_id` = `n`.`nid`')
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

        foreach ($query->each(100, $remoteDb) as $src) {
            /* Попытка исправить найденные товары с ошибками */
            if (isset(self::CHANGE_PRODUCT_FIELDS[$src['old_id']])) {
                $ch = self::CHANGE_PRODUCT_FIELDS[$src['old_id']];
                $src[$ch['field']] = $ch['newValue'];
            }
            /* Исправляем не правильный дефис и удаляем пробелы по краям */
            $src['name'] = trim(preg_replace('#‑#m', '-', $src['name']));
            /** Проверяем на наличие продукта */
            $product = Product::findOne(['name' => $src['name']]);
            if ($product) {
                if ($product->old_id != $src['old_id']) {
                    $this->notice("Продукт {$src['old_id']}:{$src['name']} пропущен. Такой уже есть.");
                    if ($this->importCreateBadProductsFile) {
                        $badProducts = [
                            'Тип ошибки' => 'Дубль товара',
                            'Номер товара' => $src['old_id'],
                            'Название продукта' => $src['name'],
                            'Номер дубля' => $product->old_id,
                            'Название дубля' => $product->name,
                        ];
                    }
                }
            } else {
                $transaction = Yii::$app->db->beginTransaction();
                /** Если продукта нет, то и брэнда (производителя) может не быть */
                if ($src['old_brand_id']) {
                    if (!$brand = ProductBrand::findOne(['old_id' => $src['old_brand_id']])) {
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
                    }
                } else {
                    $brand = null;
                }

                /** Создаем продукт */
                /** @var Product $product */
                $product = new Product();
                $src['market_upload'] = (integer)(boolean) $src['market_upload'];
                $product->setAttributes($src);
                $product->brand_id = ($brand) ? $brand->id : null;

                if (!$product->save()) {
                    $this->error("Продукт {$src['old_id']}:{$src['name']} не сохранен", $product->errors);
                    $transaction->rollBack();
                    continue;
                }

                /** Если есть рубрика для продукта, то присоединяем продукт к ней */
                /** @var ProductRubric $rubric */
                if ($rubric = ProductRubric::findOne(['old_id' => $src['old_rubric_id']])) {
                    $product2rubricParams = [
                        'rubric_id' => $rubric->id,
                        'product_id' => $product->id
                    ];
                    if (!Product2ProductRubric::findOne($product2rubricParams)) {
                        $product2rubric = new Product2ProductRubric($product2rubricParams);
                        if (!$product2rubric->save()) {
                            $this->error(
                                "Невозможно присоединить товар {$src['old_id']}:{$src['name']} к рубрике {$src['rubric_name']}",
                                $product2rubric->errors
                            );
                            $transaction->rollBack();
                            return 1;
                        }
                    }
                    $product->main_rubric_id = $rubric->id;
                    $product->save();
                } else {
                    $this->error(
                        "Не импортирована рубрика {$src['rubric_name']} для товара {$src['old_id']}:{$src['name']}. Продукт без рубрики"
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

                /** Назначаем цены, если они не были назначины */
                /** @var ProductPrice $priceParams */
                $domains = [
                    '8str' => 'price',
                    'vigsec' => 'vig_sec_price',
                ];
                foreach($domains as $domain => $field) {
                    if (empty($src[$field])) continue;
                    $priceParams = [
                        'value' => round((float)$src[$field], 2),
                        'product_id' => $product->id,
                        'domain_name' => Yii::$app->params['domains'][$domain],
                    ];
                    if (!ProductPrice::findOne($priceParams)) {
                        $price = new ProductPrice($priceParams);
                        if (!$price->save()) {
                            $this->error(
                                "Цена для товара {$src['old_id']}:{$src['name']} и домена $domain не установлена. Откат добавления товара",
                                $price->errors
                            );
                            $transaction->rollBack();
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
                foreach($tags as $tag => $srcTag) {
                    if (empty($src[$srcTag])) continue;
                    $tag = $this->getTag($tag);
                    $tag2productParams = [
                        'product_id' => $product->id,
                        'product_tag_id' => $tag->id,
                    ];
                    if (!ProductTag2product::findOne($tag2productParams)) {
                        $tag2product = new ProductTag2product($tag2productParams);
                        if (!$tag2product->save()) {
                            $this->error(
                                "Ошибка при добавлении к товару {$product->id}:{$product->name} метки {$tag->name}. Отка добавления продукта",
                                $tag2product->errors
                            );
                            $transaction->rollBack();
                            continue;
                        }
                    }
                }
                $transaction->commit();
            }

            /** Добавляем файлы */
            if (!$product->addFile($src['filename'])->save()) {
                $this->error(
                    "Картинка {$src['filename']} для продукта {$src['old_id']}:{$src['name']} не добавлена",
                    $product->errors
                );
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

    protected function exportRubrics($remoteDb) {
        $this->trace('Экспорт рубрикатора');

        /* Читаме рубрикатор */
        $query = new Query();
        $query->select('[[c]].[[tid]] as [[rubric_id]]')
            ->addSelect('[[c]].[[name]] as [[rubric_name]]')
            ->addSelect('[[p]].[[tid]] as [[parent_rubric_id]]')
            ->addSelect('[[p]].[[name]] as [[parent_rubric_name]]')
            ->from('{{%taxonomy_term_data}} as {{c}}')
            ->leftJoin('{{%taxonomy_term_hierarchy}} as {{h}}', '{{c}}.[[tid]] = {{h}}.[[tid]]')
            ->leftJoin('{{%taxonomy_term_data}} as {{p}}', '{{h}}.[[parent]] = {{p}}.[[tid]]')
            ->where(['{{c}}.[[vid]]' => 2])->orderBy('{{p}}.[[tid]]');
        
        $batch = $query->batch(100, $remoteDb);
        $rubricsPull = [];
        $createdRubrics = [];
        do {
            if (empty($rubricsPull) || !empty($rubricsPull[0]['depends_on'])) {
                $batch->next();
                $rubricsPull = array_merge((array) $batch->current(), $rubricsPull);
            }
            $currentRubric = array_shift($rubricsPull);
            
            if (!empty($currentRubric['parent_rubric_id'])) {
                if (!isset($createdRubrics[$currentRubric['parent_rubric_id']])) {
                    $currentRubric['depends_on'] = $currentRubric['parent_rubric_id'];
                    array_push($rubricsPull, $currentRubric);
                    continue;
                }
                if (!$parentRubric = ProductRubric::findOne(['old_id' => $currentRubric['parent_rubric_id']])) {
                    $this->error('Непредвиденная ошибка. Невозможно найти родительскую рубрику old_id=' .
                        $currentRubric['parent_rubric_id']);
                    return 1;
                }
                /** @noinspection MissedFieldInspection */
                $newRubric = new ProductRubric([
                    'name' => $currentRubric['rubric_name'],
                    'old_id' => $currentRubric['rubric_id'],
                    'old_parent_id' => $currentRubric['parent_rubric_id'],
                ]);
                $newRubric->appendTo($parentRubric);
            } else {
                if (!$newRubric = ProductRubric::findOne(['old_id' => $currentRubric['rubric_id']])) {
                    /** @noinspection MissedFieldInspection */
                    $newRubric = new ProductRubric([
                        'old_id' => $currentRubric['rubric_id'],
                        'name' => $currentRubric['rubric_name']
                    ]);
                    $newRubric->makeRoot();
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
                foreach(self::TABLES_AUX_FIELDS as $table) {
                    $query = Yii::$app->db->queryBuilder->delete("{{%{$table['table']}}}", [], $params);
                    Yii::$app->db->createCommand($query)->execute();
                    Yii::$app->db->createCommand("ALTER TABLE {{%{$table['table']}}} AUTO_INCREMENT=0")->execute();
                }
            } catch(\Exception $e) {
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
                        try{
                            Yii::$app->db->createCommand($query)->execute();
                        }catch (Exception $e) {
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
                try{
                    Yii::$app->db->createCommand($query)->execute();
                }catch (Exception $e) {
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
    
    protected function trace($msg) {
        $msg = $this->ansiFormat($msg, Console::FG_YELLOW);
        $this->stdout($msg . "\n");
    }
    protected function success($msg) {
        $msg = $this->ansiFormat($msg, Console::FG_GREEN);
        $this->stdout($msg . "\n");
    }
    protected function notice($msg) {
        $msg = $this->ansiFormat($msg, Console::FG_CYAN);
        $this->stdout("\t- $msg\n");
    }
    protected function error($msg, $errors = []) {
        $msg = $this->ansiFormat($msg, Console::FG_RED);
        $this->stderr($msg . "\n");
        foreach ((array)$errors as $name => $mess) {
            foreach ($mess as $mes) {
                $this->error("\t-$name: $mes");
            }
        }
    }

}
