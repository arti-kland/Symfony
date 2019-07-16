# twitter-like-symfony

Installing Symfony¶

Before creating your first Symfony application, make sure to meet the following requirements:

    Your server has PHP 7.1 or higher installed (and these PHP extensions which are installed and enabled by default by PHP);
    You have installed Composer, which is used to install PHP packages;
    You have installed the Symfony local web server, which provides all the tools you need to develop your application locally.
    
Once these requirements are installed, got to : https://symfony.com/download, choose your ios, and Download and run the setup.exe installer to create a binary called symfony.     

Open your terminal and run any of these commands to create the Symfony application:

 run : symfony new --full my_project
 
 Open your terminal, move into your new project directory and start the local web server as follows:

 run : cd my-project/
 run : server:start
 
 Open your browser and navigate to http://localhost:8000/. If everything is working, you'll see a welcome page. Later, when you are finished working, stop the server by pressing Ctrl+C from your terminal.


Storing your Project in git

Before storing make sure you create a .gitignore file at the roots of your project, with gitignore.io (choose your IDE+all)

Storing your project in services like GitHub, GitLab and Bitbucket works like with any other code project! Init a new repository with Git and you are ready to push to your remote:

 git init
 git add .
 git commit -m "Initial commit"
 git push -u origin master
