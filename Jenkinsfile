pipeline {

    agent any

    environment {
        SONAR_SCANNER = tool 'SonarScanner'
    }


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



        stage('SonarQube Backend') {

            steps {

                dir('plateforme-citoyens-avocats') {

                    withSonarQubeEnv('SonarQube') {

                        sh '${SONAR_SCANNER}/bin/sonar-scanner'

                    }
                }
            }
        }



        stage('SonarQube Frontend') {

            steps {

                dir('lexconnect-frontend') {

                    withSonarQubeEnv('SonarQube') {

                        sh '${SONAR_SCANNER}/bin/sonar-scanner'

                    }
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

            echo 'LexConnect Pipeline terminé avec succès'

        }


        failure {

            echo 'Erreur dans le pipeline'

        }

    }

}