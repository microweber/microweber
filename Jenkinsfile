#!/usr/bin/env groovy

def getKubeLabel = { Integer index, String gitBranch, String buildNumber ->
    return "microweber-unit-tests-${index}-${gitBranch}-${buildNumber}"
}

def getKubeNodeSelector = { Integer index ->
    def nodeNumber = (index % 5) + 1

    return "kubernetes.io/hostname=tests${nodeNumber}"
}

def getImageTag = { String gitBranch, String buildNumber ->
    return "${gitBranch}-${buildNumber}"
}

def components = ['microweber-modules/multilanguage', 'PYovchevski/MW-Module-Videos-Playlist']


pipeline {
  //environment {
    //registryCredential = 'microweber-dockerhub'
    //DOCKER_BUILDKIT = "1"
  //}
  agent {
    node {
      label 'master'
    }
  }
  stages {
    stage('Unit testing') {
      parallel {
        stage('PHPUnit 7.1') {
          agent {
            kubernetes {
                label "${getKubeLabel(0, BRANCH_NAME, BUILD_NUMBER)}"
                defaultContainer 'app'
                yamlFile 'build/pods/php71-phpunit.yaml'
                nodeSelector "${getKubeNodeSelector(0)}"
            }
          }
          steps {
            sh 'pwd'
            sh 'composer install -o --no-progress'
            sh 'phpunit --version'
            sh 'phpunit --log-junit "reports/unitreport-php71.xml"'
          }
	        post {
	            always {
	                junit 'reports/unitreport-php71.xml'
	            }
	        }
        }
        stage('PHPUnit 7.2') {
          agent {
            kubernetes {
                label "${getKubeLabel(1, BRANCH_NAME, BUILD_NUMBER)}"
                defaultContainer 'app'
                yamlFile 'build/pods/php72-phpunit.yaml'
                nodeSelector "${getKubeNodeSelector(1)}"
            }
          }
          steps {
            sh 'pwd'
            sh 'composer install -o --no-progress'
            sh 'phpunit --version'
            sh 'phpunit --log-junit "reports/unitreport-php72.xml"'
          }
	        post {
	            always {
	                junit 'reports/unitreport-php72.xml'
	            }
	        }
        }
        stage('PHPUnit 7.3') {
          agent {
            kubernetes {
                label "${getKubeLabel(2, BRANCH_NAME, BUILD_NUMBER)}"
                defaultContainer 'app'
                yamlFile 'build/pods/php73-phpunit.yaml'
                nodeSelector "${getKubeNodeSelector(2)}"
            }
          }
          steps {
            sh 'pwd'
            sh 'composer install -o --no-progress'
            sh 'phpunit --version'
            sh 'phpunit --log-junit "reports/unitreport-php73.xml"'
          }
	        post {
	            always {
	                junit 'reports/unitreport-php73.xml'
	            }
	        }
        }
        stage('PHPUnit 7.3 Postgres 9.6') {
          agent {
            kubernetes {
                label "${getKubeLabel(3, BRANCH_NAME, BUILD_NUMBER)}"
                defaultContainer 'app'
                yamlFile 'build/pods/php73-postgres96-phpunit.yaml'
                nodeSelector "${getKubeNodeSelector(3)}"
            }
          }
          steps {
            sh 'pwd'
            sh 'composer install -o --no-progress'
            sh 'phpunit --version'
            sh 'phpunit --log-junit "reports/unitreport-php73-postgres96.xml"'
          }
	        post {
	            always {
	                junit 'reports/unitreport-php73-postgres96.xml'
	            }
	        }
        }
        stage('PHPUnit 7.3 Postgres 11') {
          agent {
            kubernetes {
                label "${getKubeLabel(4, BRANCH_NAME, BUILD_NUMBER)}"
                defaultContainer 'app'
                yamlFile 'build/pods/php73-postgres11-phpunit.yaml'
                nodeSelector "${getKubeNodeSelector(4)}"
            }
          }
          steps {
            sh 'pwd'
            sh 'composer install -o --no-progress'
            sh 'phpunit --version'
            sh 'phpunit --log-junit "reports/unitreport-php73-postgres11.xml"'
          }
	        post {
	            always {
	                junit 'reports/unitreport-php73-postgres11.xml'
	            }
	        }
        }
        stage('PHPUnit 7.3 MySQL 5.7') {
          agent {
            kubernetes {
                label "${getKubeLabel(5, BRANCH_NAME, BUILD_NUMBER)}"
                defaultContainer 'app'
                yamlFile 'build/pods/php73-mysql57-phpunit.yaml'
                nodeSelector "${getKubeNodeSelector(5)}"
            }
          }
          steps {
            sh 'pwd'
            sh 'composer install -o --no-progress'
            sh 'phpunit --version'
            sh 'phpunit --log-junit "reports/unitreport-php73-mysql57.xml"'
          }
	        post {
	            always {
	                junit 'reports/unitreport-php73-mysql57.xml'
	            }
	        }
        }
       // stage('PHPUnit 7.3 MySQL 8.0') {
       //   agent {
       //     kubernetes {
       //         label "${getKubeLabel(6, BRANCH_NAME, BUILD_NUMBER)}"
       //         defaultContainer 'app'
       //         yamlFile 'build/pods/php73-mysql80-phpunit.yaml'
       //         nodeSelector "${getKubeNodeSelector(6)}"
       //     }
       //   }
       //   steps {
       //     sh 'pwd'
       //     sh 'composer install -o --no-progress'
       //     sh 'phpunit --version'
       //     sh 'phpunit --log-junit "reports/unitreport-php73-mysql80.xml"'
       //   }
	   //     post {
	   //         always {
	   //             junit 'reports/unitreport-php73-mysql80.xml'
	   //         }
	   //     }
       // }
        stage('PHPUnit 7.4') {
          agent {
            kubernetes {
                label "${getKubeLabel(7, BRANCH_NAME, BUILD_NUMBER)}"
                defaultContainer 'app'
                yamlFile 'build/pods/php74-phpunit.yaml'
                nodeSelector "${getKubeNodeSelector(7)}"
            }
          }
          steps {
            sh 'pwd'
            sh 'composer install -o --no-progress'
            sh 'phpunit --version'
            sh 'phpunit --log-junit "reports/unitreport-php74.xml"'
          }
	        post {
	            always {
	                junit 'reports/unitreport-php74.xml'
	            }
	        }
        }
      }
    }
//
//    stage('Components testing') {
//      parallel {
//        stage('Component Test') {
//    			agent {
//              kubernetes {
//                  label "${getKubeLabel(0, BRANCH_NAME, BUILD_NUMBER)}"
//                  defaultContainer 'app'
//                  yamlFile 'build/pods/php71-phpunit.yaml'
//                  nodeSelector "${getKubeNodeSelector(0)}"
//  				}
//  			}
//          steps {
//            sh 'pwd'
//            sh 'composer install -o --no-progress'
//            script {
//              for (int i = 0; i < components.size(); ++i) {
//  							sh "composer require ${components[i]} --no-cache"
//  						}
//  				  }
//            sh 'phpunit --version'
//            sh 'phpunit --log-junit "reports/components.tests.xml"'
//  			}
//  	        post {
//  	            always {
//  	                junit 'reports/components.tests.xml'
//  	            }
//              }
//            }
//          }
//        }
//

    stage('UI Testing') {
      parallel {
        stage('PHP 7.3 Nginx-fpm') {
          agent {
            kubernetes {
                label "${getKubeLabel(0, BRANCH_NAME, BUILD_NUMBER)}"
                defaultContainer 'app'
                yamlFile 'build/pods/php73-mysql57-nginx-fpm-cypress.yaml'
                nodeSelector "${getKubeNodeSelector(0)}"
            }
          }
          steps {
            sh 'composer install -o --no-progress'
            sh 'php artisan microweber:install admin@admin.com admin password localhost mysql root mysql -p site_ -t dream -d 1'
            sh 'cp -r ./ /var/www/html'
            sh 'chown -R www-data:www-data /var/www/html/ && chmod -R 755 /var/www/html/'
            container('cypress') {
                checkout([
                           $class: 'GitSCM',
                           branches: [[name: 'refs/heads/master']],
                           doGenerateSubmoduleConfigurations: false,
                           extensions: [[$class: 'RelativeTargetDirectory', relativeTargetDir: 'e2e-electron-nginx-fpm']],
                           submoduleCfg: [],
                           userRemoteConfigs: [[credentialsId: 'github-credentials-brightside', url: 'https://github.com/microweber-dev/cypress-tests.git']]
                       ])
                sh 'DISPLAY= cypress run --project ./e2e-electron-nginx-fpm/ --reporter junit --reporter-options mochaFile=reports/unitreport-php73-mysql57-nginx-fpm-electron-cypress.xml,toConsole=true'
            }
          }
	        post {
	            always {
                container('cypress') {
	                junit 'e2e-electron-nginx-fpm/reports/unitreport-php73-mysql57-nginx-fpm-electron-cypress.xml'
                }
	            }
              failure {
                container('cypress') {
                  stash allowEmpty: true, name: 'e2e-electron-nginx-fpm-videos', includes: 'e2e-electron-nginx-fpm/cypress/videos/**/*'
                  stash allowEmpty: true, name: 'e2e-electron-nginx-fpm-screenshots', includes: 'e2e-electron-nginx-fpm/cypress/screenshots/**/*'
                }
              }
	        }
        }
        stage('PHP 7.3 Apache2') {
          agent {
            kubernetes {
                label "${getKubeLabel(1, BRANCH_NAME, BUILD_NUMBER)}"
                defaultContainer 'app'
                yamlFile 'build/pods/php73-mysql57-apache2-cypress.yaml'
                nodeSelector "${getKubeNodeSelector(1)}"
            }
          }
          steps {
            sh 'composer install -o --no-progress'
            sh 'php artisan microweber:install admin@admin.com admin password localhost mysql root mysql -p site_ -t dream -d 1'
            sh 'cp -r ./ /var/www/html'
            sh 'chown -R www-data:www-data /var/www/html/ && chmod -R 755 /var/www/html/'
            container('cypress') {
                checkout([
                           $class: 'GitSCM',
                           branches: [[name: 'refs/heads/master']],
                           doGenerateSubmoduleConfigurations: false,
                           extensions: [[$class: 'RelativeTargetDirectory', relativeTargetDir: 'e2e-electron-apache2']],
                           submoduleCfg: [],
                           userRemoteConfigs: [[credentialsId: 'github-credentials-brightside', url: 'https://github.com/microweber-dev/cypress-tests.git']]
                       ])
                sh 'apt-get install tree -y'
                sh 'DISPLAY= cypress run --project ./e2e-electron-apache2/ --reporter junit --reporter-options mochaFile=reports/unitreport-php73-mysql57-apache2-electron-cypress.xml,toConsole=true'
            }
          }
	        post {
	            always {
                container('cypress') {
	                junit 'e2e-electron-apache2/reports/unitreport-php73-mysql57-apache2-electron-cypress.xml'
                }
	            }
              failure {
                container('cypress') {
                  stash allowEmpty: true, name: 'e2e-electron-apache2-videos', includes: 'e2e-electron-apache2/cypress/videos/**/*'
                  stash allowEmpty: true, name: 'e2e-electron-apache2-screenshots', includes: 'e2e-electron-apache2/cypress/screenshots/**/*'
                  sh 'tree'
                }
              }
	        }
        }
        stage('PHP 7.3 Apache2 Chrome') {
          agent {
            kubernetes {
                label "${getKubeLabel(2, BRANCH_NAME, BUILD_NUMBER)}"
                defaultContainer 'app'
                yamlFile 'build/pods/php73-mysql57-apache2-cypress.yaml'
                nodeSelector "${getKubeNodeSelector(2)}"
            }
          }
          steps {
            sh 'composer install -o --no-progress'
            sh 'php artisan microweber:install admin@admin.com admin password localhost mysql root mysql -p site_ -t dream -d 1'
            sh 'cp -r ./ /var/www/html'
            sh 'chown -R www-data:www-data /var/www/html/ && chmod -R 755 /var/www/html/'
            container('cypress') {
                checkout([
                           $class: 'GitSCM',
                           branches: [[name: 'refs/heads/master']],
                           doGenerateSubmoduleConfigurations: false,
                           extensions: [[$class: 'RelativeTargetDirectory', relativeTargetDir: 'e2e-chrome-apache2']],
                           submoduleCfg: [],
                           userRemoteConfigs: [[credentialsId: 'github-credentials-brightside', url: 'https://github.com/microweber-dev/cypress-tests.git']]
                       ])
                sh 'DISPLAY= cypress run --browser chrome --project ./e2e-chrome-apache2/ --reporter junit --reporter-options mochaFile=reports/unitreport-php73-mysql57-apache2-chrome-cypress.xml,toConsole=true'
            }
          }
	        post {
	            always {
                container('cypress') {
	                junit 'e2e-chrome-apache2/reports/unitreport-php73-mysql57-apache2-chrome-cypress.xml'
                }
	            }
              failure {
                container('cypress') {
                  stash allowEmpty: true, name: 'e2e-chrome-apache2-screenshots', includes: 'e2e-chrome-apache2/cypress/screenshots/**/*'
                }
              }
	        }
        }
      }
    }
  }


  post {
    failure {
      dir('artifacts') {
        script {
          try {
              unstash 'e2e-electron-nginx-fpm-screenshots'
          } catch (Exception e) {
              sh 'echo "Handle the exception!"'
          }
        }
        script {
          try {
              unstash 'e2e-electron-nginx-fpm-videos'
          } catch (Exception e) {
              sh 'echo "Handle the exception!"'
          }
        }
        script {
          try {
              unstash 'e2e-electron-apache2-videos'
          } catch (Exception e) {
              sh 'echo "Handle the exception!"'
          }
        }
        script {
          try {
              unstash 'e2e-electron-apache2-screenshots'
          } catch (Exception e) {
              sh 'echo "Handle the exception!"'
          }
        }
        script {
          try {
              unstash 'e2e-chrome-apache2-screenshots'
          } catch (Exception e) {
              sh 'echo "Handle the exception!"'
          }
        }
        script {
          try {
              archiveArtifacts artifacts: "**/*", fingerprint: true
          } catch (Exception e) {
              sh 'echo "Handle the exception!"'
          }
        }
      }
    }
    cleanup {
      deleteDir()
    }
  }
}
