@Library('jenkins-build-helpers') _
setupEnvironment(['business_unit': 'corp'])

def createTestingEnvironment() {
    return setupContainers([
        [
            'name': 'main',
            'image': 'ike-docker-local.artifactory.internetbrands.com/corp/levelup-academy:oobidiya-testing-image',
            'imagePullPolicy': 'Always',
            'env': [
                ['name': 'DB_HOST',     'value': 'localhost'],
                ['name': 'PGPASSWORD',  'value': 'password'],
                ['name': 'DB_DATABASE', 'value': 'testing'],
                ['name': 'DB_USERNAME', 'value': 'sail'],
                ['name': 'DB_PASSWORD', 'value': 'password'],
                ['name': 'LOG_CHANNEL', 'value': 'single'],
                ['name': 'LOG_LEVEL',   'value': 'debug'],
            ],
        ],[
           'name': 'pgsql',
           'image': 'postgres:14',
           'env': [
                ['name': 'PGPASSWORD',        'value': 'password'],
                ['name': 'POSTGRES_DB',       'value': 'testing'],
                ['name': 'POSTGRES_USER',     'value': 'sail'],
                ['name': 'POSTGRES_PASSWORD', 'value': 'password'],
          ]
        ]
   ])
}

def checkBranchName(name) {
    if(name.startsWith("build") || name.startsWith("ci")|| name.startsWith("docs")
        || name.startsWith("feat") || name.startsWith("fix") || name.startsWith("perf")
        || name.startsWith("refactor") || name.startsWith("style")|| name.startsWith("test")) {
        return true
    }
    return false
}

pipeline {
    agent none

    options {
        gitLabConnection('IB Gitlab')
    }

    stages {
        stage('Build pipeline testing image') {
            agent {
                kubernetes {
                    yaml dockerContainerImageBuildAndPushPodManifest()
                }
            }
            steps {
                container('builder') {
                    dockerContainerImageBuildAndPush([
                        'docker_repo_host': 'ike-docker-local.artifactory.internetbrands.com',
                        'docker_repo_credential_id': 'artifactory-ike',
                        'dockerfile': './pipeline/Dockerfile',
                        'docker_image_name': 'levelup-academy',
                        'docker_image_tag': 'oobidiya-testing-image'
                    ])
                }
            }
        }

        stage('Check merge requests') {
            when {
                expression { env.CHANGE_TARGET != null }
            }
            post {
                success {
                    updateGitlabCommitStatus name: 'mr-commits', state: 'success'
                }
                failure {
                    updateGitlabCommitStatus name: 'mr-commits', state: 'failed'
                }
            }
            parallel {
                stage('Check branch name') {
                    agent {
                        kubernetes {
                            yaml createTestingEnvironment()
                        }
                    }
                    steps {
                        script {
                            echo "MR branch name ${env.CHANGE_BRANCH}"
                            def branch_name = env.CHANGE_BRANCH
                            if (checkBranchName(branch_name)) {
                                echo "This is a valid MR branch name"
                            }else {
                                error "This branch name does not start with one of a recommended branch-name word"
                            }
                        }

                    }
                }
                stage('Check commit email address') {
                    agent {
                        kubernetes {
                            yaml createTestingEnvironment()
                        }
                    }
                    steps {
                        script {
                            def emails = sh(script: "git log --pretty=format:%ae remotes/origin/${env.CHANGE_TARGET}..remotes/origin/${env.BRANCH_NAME}", returnStdout: true).trim().split('\n')
                            for(email in emails) {
                                if(!email.endsWith('@internetbrands.com')) {
                                    error "Email error: authors' email address does not contain '@internetbrands.com'"
                                }
                            }
                            echo "All commits contain '@internetbrands.com' suffix"
                        }
                    }
                }
            }
        }

        stage('Run PHP tests') {
            agent {
                kubernetes {
                    yaml createTestingEnvironment()
                }
            }
            post {
                success {
                    updateGitlabCommitStatus name: 'php-tests', state: 'success'
                }
                failure {
                    updateGitlabCommitStatus name: 'php-tests', state: 'failed'
                }
            }
            steps {
                container('main') {
                    // For reference see: https://plugins.jenkins.io/gitlab-branch-source/#plugin-content-environment-variables
                    // (variables such as this will be useful for your homework).
                    sh 'echo "Branch $BRANCH_NAME going into $CHANGE_TARGET implemented by $CHANGE_AUTHOR"'

                    sh '''
                        composer install
                    '''

                    sh '''
                        APP_ENV=testing php artisan test --env=testing
                    '''
                }
            }
        }
    }
}
