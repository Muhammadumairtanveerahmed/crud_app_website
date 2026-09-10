pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Docker Build') {
            steps {
                sh 'docker build -t crud-app:latest .'
            }
        }

        stage('Test') {
            steps {
                sh 'docker image inspect crud-app:latest'
                echo 'Docker image test successful!'
            }
        }

        stage('Deploy') {
            steps {
                sh 'docker rm -f crud-app-container || true'
                sh '''
                docker run -d --name crud-app-container \
                  --network crud_app_website_default \
                  -e DB_HOST=db \
                  -e DB_USER=crud_user \
                  -e DB_PASS=crud_pass \
                  -e DB_NAME=crud_app \
                  -p 8081:80 crud-app:latest
                '''
            }
        }
    }

    post {
        success {
            echo 'CRUD application deployed successfully!'
        }

        failure {
            echo 'Pipeline failed!'
        }
    }
}
