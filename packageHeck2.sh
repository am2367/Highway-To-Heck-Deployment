#!/bin/bash

#checks for arguments when the script is ran
heckStatus=""
if [ $# -lt 1 ] ; then
	#no arguments defaults to "working"
	heckStatus="working"
	echo "Bundle marked as working"
	#echo $heckStatus
elif [ $# -ge 2 ] ; then
	#if more than 2 arguments script stops
	echo "Too many arguments, only one argument accepted"
	#echo $heckStatus
	exit
elif [ $1 == "notworking" ] ; then
	#accepts the "notworking" argument
	heckStatus="notworking"
	echo "Bundle marked as notworking"
	#echo $heckStatus
elif [ $1 == "QA" ] ; then
	#accepts the "QA" argument
	heckStatus="QA"
	echo "Bundle marked as QA"
	#echo $heckStatus
elif [ $1 != "notworking" ] || [ $1 != "QA" ]; then
	#rejects any other argument
	echo "Not a valid argument (only 'notworking' or 'QA' are valid)"
	exit
fi

#sets the version variable to the current date/time
version=$(date +'%Y-%m-%d-%H-%M-%S');
echo "Version: "$version

#bundles the two directories into a .tar file and appends the version variable to the file name
cd /home/$USER
tar -cf heck_$version.tar git/it490f17 /var/www/html

#directory of bundle created
tardir=/home/pc329/bundles/heck_$version.tar
echo "Directory: "$tardir

#send .tar file to deployment server
sshpass -p "pc329pw" scp heck_$version.tar pc329@192.168.1.46:/home/pc329/bundles
rm -rf heck_$version.tar

#runs php script with 4 arguments 
#php /home/$USER/bin/deployFunctions.php "package" "$tardir" "$heckStatus" "latest"
sshpass -p "pc329pw" ssh pc329@192.168.1.46 "php -f /home/pc329/bin/deployFunctions.php 'package' '$tardir' '$heckStatus' ''"

