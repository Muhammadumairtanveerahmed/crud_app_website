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
                sh 'docker image inspect crud-app:latest > /dev/null'
                echo 'Docker image test successful!'
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                    docker rm -f crud-app-container || true
                    docker run -d --name crud-app-container -p 80:80 crud-app:latest
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
