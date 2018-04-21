require 'yaml'
require 'fileutils'

required_plugins = %w( vagrant-hostmanager vagrant-vbguest vagrant-hostsupdater )
required_plugins.each do |plugin|
    exec "vagrant plugin install #{plugin}" unless Vagrant.has_plugin? plugin
end

VAGRANTFILE_API_VERSION ||= "2"

confDir = $confDir ||= File.expand_path("vagrant", File.dirname(__FILE__))
configYamlPath = File.expand_path(confDir + '/config.yaml')

afterRootScriptPath = File.expand_path(confDir + "/scripts/once-as-root.sh")
afterNoneRootScriptPath = File.expand_path(confDir + "/scripts/once-as-vagrant.sh")
AlwaysRootScriptPath = File.expand_path(confDir + "/scripts/always-as-root.sh")
AlwaysNoneRootScriptPath = File.expand_path(confDir + "/scripts/always-as-vagrant.sh")

aliasesPath = File.expand_path(confDir + "/aliases")
bashCompletionsPath = File.expand_path(confDir + "/scripts/bash_autocompletion/yii")

require File.expand_path(confDir + '/scripts/configure.rb')

Vagrant.require_version '>= 1.9.0'

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

    if File.exist? aliasesPath then
        config.vm.provision "file", source: aliasesPath, destination: "/tmp/bash_aliases"
        config.vm.provision "shell" do |s|
            s.privileged = false
            s.inline = "awk '{ sub(\"\r$\", \"\"); print }' /tmp/bash_aliases > /home/vagrant/.bash_aliases"
        end
    end

    if File.exist? bashCompletionsPath then
        config.vm.provision "file", source: bashCompletionsPath, destination: "/tmp/bash_completion.d/yii"
        config.vm.provision "shell" do |s|
            s.inline = "awk '{ sub(\"\r$\", \"\"); print }' /tmp/bash_completion.d/yii > /etc/bash_completion.d/yii"
        end
    end


    if File.exist? configYamlPath then
        settings = YAML::load(File.read(configYamlPath))
    else
        abort "Settings file (#{configYamlPath}) not found"
    end

    # check github token
    if settings['github_token'].nil? || settings['github_token'].to_s.length != 40
      puts "You must place REAL GitHub token into configuration:\n#{configYamlPath}"
      exit
    end

    config.vm.box_check_update = settings['box_check_update'] ||= false

	if settings.include? 'folders'
		settings["folders"].each_with_index do |folder, index|
			settings["folders"][index]["map"] = File.expand_path(settings["folders"][index]["map"], File.dirname(__FILE__))
		end
	end

	Configure.configure(config, settings)

    if File.exist? afterRootScriptPath then
        config.vm.provision "shell", path: afterRootScriptPath, args: [settings['timezone'] ||= 'Europe/Moscow']
    end
    if File.exist? afterNoneRootScriptPath then
        config.vm.provision "shell", path: afterNoneRootScriptPath, args: [settings['github_token']], privileged: false
    end
    if File.exist? AlwaysRootScriptPath then
        config.vm.provision "shell", path: AlwaysRootScriptPath, run: 'always'
    end
    if File.exist? AlwaysNoneRootScriptPath then
        config.vm.provision "shell", path: AlwaysNoneRootScriptPath, run: 'always', privileged: false
    end

    # Install Sphinxsearch If Necessary
    if settings.has_key?("sphinxsearch") && settings["sphinxsearch"]
        config.vm.provision "shell" do |s|
            s.path = confDir + '/scripts/install-sphinxsearch.sh'
        end
    end


    #if defined? VagrantPlugins::Hostsupdater
    #    config.hostsupdater.aliases = settings['sites'].map { |site| site['map'] }
    #end

    # hosts settings (host machine)
    config.vm.provision :hostmanager
    config.hostmanager.enabled            = true
    config.hostmanager.manage_host        = true
    config.hostmanager.ignore_private_ip  = false
    config.hostmanager.include_offline    = true
    config.hostmanager.aliases            = settings['sites'].map { |site| site['map'] }

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use.
  # config.vm.provision "shell", inline: <<-SHELL
  #   apt-get update
  #   apt-get install -y apache2
  # SHELL
end
