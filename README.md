# About LRR

LRR (Lab Report Repository) is an online software application for posting assignments, submitting assignments and marking (re-marking) assignments.

This software was originally developed by by Mahomed Nor, a graduate student in the Department of Computer Science at the Zhejiang Normal University,
while he was taking a graduate course called **Advanced Software Engineering** (http://lanlab.org/course/2018f/se/homepage.html).

The LRR's project home page is at http://118.25.96.118/nor/homepage/index.html.

### If you want to get the local deployment version, clone the following

`git clone https://github.com/dzr201732120115/LRR.git`

# Mission

Our mission is to make the experience of submitting assignments great for tens of hundreds of students in the department of computer science at the Zhejiang Normal University (Jinhua City, Zhejiang Province).



# Installation Instructions

Check file INSTALLATION.md (TBA) for details.




# Current Status

This software has been actively used by students who took or are
taking courses (Introduction to Software Engineering and Software
Project Management) taught by Hui.

There are more than 200 student accounts created since its first
launch in 2018.

A running instance of this software is at http://118.25.96.118/nor/

There are about 40 bugs (most being CRITICAL) that remain unresolved
before LRR can hit its beta release.  See the section *The Bug
Tracker* for more detail.  Currently, there are a few groups (formed
by students who are taking Software Project Management) working on
these bugs.




# The Bug Tracker

We use Bugzilla to track LRR's bugs and feature requests.

Most bugs of this software are recorded on the  bug tracker for LRR:
http://118.25.96.118/bugzilla/describecomponents.cgi?product=Lab%20Report%20Repository%20%28nor%20houzi%29



# TODO

-  *Receiving email for password resetting*. Password resetting link is not always sent successfully.

-  *How assignements should be stored?*  Creating sub-directories on all student submissions course-code/semester/section-number.  (/student-number/course-code/semester/section-number/assignement-title/submission.txt)

-  *Feature request*. Editing the assignment title after uploading a new assignment (instructor).

-  [SOLVED] A new user could not login immediately after sign up.

- A more complete list of TODO's is at http://lanlab.org/course/2020s/spm/decide-areas-for-improvement-review.html


# How to Contribute

We welcome your participation in this project.

Your participation does not have to be in the form of contributing code.  You could help us on
ideas, suggestions, documentation, etc.


You need to be an invited member of *Lan Laboratory* before you can
push your feature branch or bugfix branch to the central reops at
https://github.com/lanlab-org

Send Hui (lanhui at zjnu.edu.cn) an email message including your
GitHub account name so that he could invite you to be a member of *Lan
Laboratory*.

As of March 31 2020, there are 30 members in *Lan Laboratory* (https://github.com/orgs/lanlab-org/people).

You will use the feature-branching workflow (see below) when
interacting with the central repo.  The main point of this workflow is
that you work on a branch on your local drive, push that branch to the
central repo, and create a Pull Request (i.e., Pull Me Request) at
GitHub for other people to review your changes.  When everything is
OK, then *someone* could merge your changes to the master branch in the
central repo.

I believe that *code review* at the Pull Request stage is important
for both improving code quality and improving team quality.



## The Feature-branching Workflow

We will use the feature-branching workflow for collaboration.  The
idea is that you make your own branch, work on it, and push this branch to
the central repo for review.

Check the section **The feature-branching workflow** in the following link for more detail:

https://github.com/spm2020spring/TeamCollaborationTutorial/blob/master/team.rst



## Communications Method

For real-time communication, check our IRC channel `#lrr` at irc.freenode.org.  Check this link http://lanlab.org/course/2020s/spm/irc-instruction.txt
for how to use IRC.

To submit bug reports or improvement ideas, please ask Hui [lanhui at zjnu.edu.cn] to open a Bugzilla account for you.





## Frequently Asked Questions

Check FAQ.md (TBA) for details.



# The Original GitHub Repo

The original GitHub Repo is at https://github.com/EngMohamedNor/LabReportRepo


### Steps of local test deployment
1.download **xampp** <br>
2.follow the website to do: 
> https://blog.csdn.net/qing666888/article/details/81914389 <br>

**note:** don't change mysql's **port** and don't set its **password** <br>
3.put LRR file in .\xampp\htdocs\LRR <br>
4.run http://localhost:8081/ (if you change apache's port from 80 to 8081),login phpMyAdmin <br>
5.create database lrr in phpMyAdmin and import lrr(1).sql (or lrr_database.sql). <br>
6.ensure apache and xampp server is running on Xampp-control,
run http://localhost:8081/LRR/script.php first,then run http://localhost:8081/LRR/index.php. <br>
7.use account and password in phpMyAdmin's lrr.users_table to login. <br>
8.begin your test

# Contributor List


(Please put your name and student number below.)

TanakaMichelle - Tanaka Michelle Sandati - 201732120134

WhyteAsamoah   - Yeboah Martha Asamoah   - 201732120135

xiaoyusoil - ZhengXiaoyu - 201732120110


Benny123-cell - ZhangBin - 201732120127

421281726 - LiJiaxing - 201732120118

zhenghongyu-david - ZhengHongyu - 201732120128

wkytz - YeHantao - 201732120125

zego000 - GaoZeng - 201732120117

Richard1427 - XieJiacong - 201732120123

yutengYing - YingYuteng - 201732120126

Samrusike  - Samantha Rusike  - 201632120140

Teecloudy  - Ashly Tafadzwa Dhani - 201632120150

GuedaliaBonheurSPM - Guedalia Youma - 201925800221

ACorneille - Alimasi Corneille - 201925800168

Tabithakipanga - Kipanga Dorcas - 201925800170

Daizerong - 201732120115