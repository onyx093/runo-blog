@Library('jenkins-build-helpers') _
setupEnvironment(['business_unit': 'corp'])

def createTestingEnvironment() {
    return setupContainers([
        [
            'name': 'main',
            // WARNING: rename this to follow $username-testing-image
            'image': 'ike-docker-local.artifactory.internetbrands.com/corp/levelup-academy:main-repo-testing-image',
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

pipeline {
    agent none

    options {
        gitLabConnection('IB Gitlab')
    }

    // hello, something changed

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
                        'docker_image_tag': 'main-repo-testing-image' // WARNING: rename this to follow $username-testing-image
                    ])
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
