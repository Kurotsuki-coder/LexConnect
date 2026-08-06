pipeline {

    agent any

    stages {

        stage('Build Backend Laravel') {

            steps {

                dir('plateforme-citoyens-avocats') {

                    sh 'docker build -t lexconnect-backend .'

                }
            }
        }


        stage('Build Frontend Angular') {

            steps {

                dir('lexconnect-frontend') {

                    sh 'docker build -t lexconnect-frontend .'

                }
            }
        }


        stage('Deploy Backend') {

            steps {

                sh '''
                docker stop lexconnect-backend || true
                docker rm lexconnect-backend || true

                docker run -d \
                --name lexconnect-backend \
                -p 8000:9000 \
                lexconnect-backend
                '''
            }
        }


        stage('Deploy Frontend') {

            steps {

                sh '''
                docker stop lexconnect-frontend || true
                docker rm lexconnect-frontend || true

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

            echo 'Déploiement LexConnect réussi 🚀'

        }


        failure {

            echo 'Pipeline LexConnect échoué'

        }
    }
}