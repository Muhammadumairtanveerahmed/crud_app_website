pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build') {
            steps {
                echo 'Building CRUD application...'
            }
        }

        stage('Test') {
            steps {
                echo 'Testing CRUD application...'
            }
        }
    }

    post {
        success {
            echo 'CRUD application pipeline completed successfully!'
        }

        failure {
            echo 'Pipeline failed!'
        }
    }
}
