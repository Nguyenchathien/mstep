#!/bin/sh

function consoleWait {

	while :
		do
			echo -n "DO YOU WANT TO CONTINUE [Y/N]: "
			read ans
			case $ans in
			[yY]) return 0 ;;
			[nN]) return 1 ;;
		esac
	done
}

function isDevelop(){
	
	HOSTNAME=`hostname`

	if [ `echo $HOSTNAME | grep spc` ]; then
		
		echo 2
	
	elif [ `echo $HOSTNAME | grep -i EDWARD` ]; then

		echo 3

    elif [ `echo $HOSTNAME | grep -i HIENNGUYEN` ]; then

        echo 4

	elif [ `echo $HOSTNAME | grep -i hayahide` ]; then

		echo 0
	else
		
		echo 1
	fi

}
