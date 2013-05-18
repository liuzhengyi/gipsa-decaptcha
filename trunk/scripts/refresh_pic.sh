#! /bin/sh -

if [ $# -lt 3  ]
then 
	echo "Usage: $0 url type dir [num1-analyse=10] num2-learn=10] [num3-test=10]";
	exit;
fi

URL=$1;
TYPE=$2;
BASEDIR=$3;
NUM1=$4;
DIR1='../images/analyse/';
${NUM1:=10}
NUM2=$5;
DIR2='../images/learn-sample/';
${NUM2:=10}
NUM3=$6;
DIR3='../images/test/';
${NUM3:=10}

REALDIR=$DIR1$BASEDI/
FILES=*.$TYPE
while [ $NUM1 -gt 0 ]; do 
	echo $NUM1;
	NUM1=$(($NUM1-1));
	SUFFIX='?xxx='$NUM1;
	REALURL=$URL$SUFFIX;
	#wget -O $DIR1$BASEDIR/$NUM1.$TYPE $REALURL;
	wget -O $REALDIR$NUM1.$TYPE $REALURL;
	#echo  $DIR1$BASEDIR/$NUM1.$TYPE $REALURL;
done

while [ $NUM2 -gt 0 ]; do 
	echo $NUM2;
	NUM2=$(($NUM2-1));
	SUFFIX='?xxx='$NUM2;
	REALURL=$URL$SUFFIX;
	wget -O $DIR2$BASEDIR/$NUM2.$TYPE $REALURL;
	#echo  $DIR2$BASEDIR/$NUM2.$TYPE $REALURL;
done

while [ $NUM3 -gt 0 ]; do 
	echo $NUM3;
	NUM3=$(($NUM3-1));
	SUFFIX='?xxx='$NUM3;
	REALURL=$URL$SUFFIX;
	wget -O $DIR3$BASEDIR/$NUM3.$TYPE $REALURL;
	#echo  $DIR3$BASEDIR/$NUM3.$TYPE $REALURL;
done
