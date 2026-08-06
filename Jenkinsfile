pipeline {

    agent any

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }


        stage('Backend Laravel') {

            steps {

                dir('plateforme-citoyens-avocats') {

                    sh 'composer install --no-interaction --prefer-dist'

                    sh 'php artisan test'

                    withSonarQubeEnv('SonarQube') {
                        sh 'sonar-scanner'
                    }

                    sh 'docker build -t lexconnect-backend .'
                }
            }
        }


        stage('Frontend Angular') {

            steps {

                dir('lexconnect-frontend') {

                    sh 'npm ci'

                    withSonarQubeEnv('SonarQube') {
                        sh 'sonar-scanner'
                    }

                    sh 'docker build -t lexconnect-frontend .'
                }
            }
        }


        stage('Deploy Docker') {

            steps {

                sh '''
                docker stop lexconnect-backend || true
                docker rm lexconnect-backend || true

                docker stop lexconnect-frontend || true
                docker rm lexconnect-frontend || true


                docker run -d \
                --name lexconnect-backend \
                -p 8000:9000 \
                lexconnect-backend


                docker run -d \
                --name lexconnect-frontend \
                -p 4200:80 \
                lexconnect-frontend
                '''
            }
        }
    }


    post {

        success {
            echo 'Déploiement LexConnect terminé avec succès'
        }

        failure {
            echo 'Pipeline LexConnect échoué'
        }
    }
}