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
                bat 'docker build -t crud-app:latest .'
            }
        }

        stage('Test') {
            steps {
                bat 'docker image inspect crud-app:latest'
                echo 'Docker image test successful!'
            }
        }

        stage('Deploy') {
            steps {
                bat 'docker rm -f crud-app-container 2>NUL || exit /b 0'
                bat 'docker run -d --name crud-app-container -p 80:80 crud-app:latest'
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