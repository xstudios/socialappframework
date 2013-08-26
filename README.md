# Social-App-Framework

* * *

Developed by Tim Santor, Social App Framework (SAF) is a PHP framework with a very
small footprint, built for PHP programmers who need a simple, straight-forward
toolkit to create full-featured Facebook tabs and applications. Its goal is to
enable you to get projects up and running much faster than you could if you were
writing code to implement the Facebook PHP/JavaScript SDK's from scratch, by
providing a set of libraries for commonly needed tasks.

###Background
Social App Framework was not something thrown together overnight. The framework
has evolved monthly, weekly, if not daily over the course of a year and a half.
The framework is very stable and has been "battle tested" over the course of that
time by powering around 20+ unique Facebook Tab, Canvas and Facebook Connect
applications. It is through that rigourous testing with various app types that
SAF has evolved to its current state where it can get you up and running with
any type of Facebook app (Tab, Canvas or Facebook Connect) in a matter of minutes.

## More info (Coming soon)
[http:://socialappframework.com](http:://socialappframework.com)

## Requirements
- PHP >= 5.2.9
- Facebook PHP SDK >=3.2.2

## File Structure
- vendors/
    - facebook/
        - php-sdk/
            - examples/
            - src/
            - tests/

    - tsantor/
        - socialappframework/
            - docs/
            - src/
            - tests/

##Making pull requests

1. Before anything, make sure to update the _MAIN SAF REPOSITORY_.

        $ git checkout master
        $ git pull origin master

1. Once updated with the latest code, create a new branch with a branch name
describing what your changes are. Possible types (bugfix, feature, improvement):
   
        $ git checkout -b bugfix/fix-signed-request

1. Make your code changes. Always make sure to sign-off (-s) on all commits made

        $ git commit -s -m "Commit message"

1. Once you've committed all the code to this branch, push the branch to your
_FORKED SAF REPOSITORY_

        $ git push fork bugfix/fix-signed-request

1. Go back to your _FORKED SAF REPOSITORY_ on GitHub and submit a pull request.
1. I will review your code and merge it in when it has been classified as suitable.
