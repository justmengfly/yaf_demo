#!/bin/bash
    
ENV=$1
ROLE=$2
PRODUCT=$3
SCRIPTPATH=`dirname $0`
declare -a ARRAY


RELATIVE_PATH=${SCRIPTPATH}

if [ X$PRODUCT = X"particle" ];then
    TEMPLATE_DIR=${RELATIVE_PATH}/particle_template
    CONFIG_DIR=${RELATIVE_PATH}/particle_conf/$ENV
else
    TEMPLATE_DIR=${RELATIVE_PATH}/yidian_template
    CONFIG_DIR=${RELATIVE_PATH}/yidian_conf/$ENV
fi
RECIPE_DIR=${RELATIVE_PATH}/recipe

CURRENT_TEMPLATE="";

#return trimed line
function trim()
{
    LINE=$1
    NEWLINE=`echo $LINE | sed 's/^ *//g' | sed 's/ *$//g'`
    echo $NEWLINE
}

#there are two types of settings
# a. env related settings
# b. common settings
#return value
function get_setting_value()
{
    CURRENT_TEMPLATE=$1
    MACRO=$2
    SPECIFICCONFFILE=${CONFIG_DIR}/$CURRENT_TEMPLATE
    SHARECONFFILE=${CONFIG_DIR}/configure.conf
    SERVICEFILE=${CONFIG_DIR}/shared_ip_macros.conf
    GLOBALSHAREFILE=${SCRIPTPATH}/../conf/shared_us_prod.conf

    if [ -r $GLOBALSHAREFILE ]; then
        cat "$GLOBALSHAREFILE" >> temp.out
        echo "" >> temp.out
    fi

    if [ -r $SERVICEFILE ]; then
        INCLUDE_FILE=`grep "^include" $SERVICEFILE | awk -F"include" '{print $2}'`
        if [ "$INCLUDE_FILE" != "" ]; then
            INCLUDE_NAME=$(trim $INCLUDE_FILE)
            INCLUDEPATH=${CONFIG_DIR}/$INCLUDE_NAME
            cat "$INCLUDEPATH" > temp.out
            cat "$SERVICEFILE" >> temp.out
            echo "" >> temp.out
        else 
            cat "$SERVICEFILE" > temp.out
            echo "" >> temp.out
        fi
    fi

    if [ -r $SHARECONFFILE ]; then
        cat "$SHARECONFFILE" >> temp.out
        echo "" >> temp.out
    fi

    if [ -r $SPECIFICCONFFILE ]; then
        cat "$SPECIFICCONFFILE" >> temp.out
        echo "" >> temp.out
    fi

    if [ -r temp.out ]; then
        VALUE=`grep ^${MACRO}= temp.out | awk -F"=" '{print $2}' | head -1`
              `rm temp.out`
        echo $(trim "$VALUE")
    fi
}

function get_files()
{
    if [ -r $1 ]; then
        ARRAY=`ls $1`
    fi
}

#return the NEWLINE
function replace_macro()
{
    TEMPLATE_FILE=$1
    LINE=$2
    MACRO_NUMBER=`echo $LINE | awk -F"@@" '{print (NF - 1)/2}'`
    TEMPLINE=$LINE
    for ((i=0;i<$MACRO_NUMBER;i++));do
        MACRO=`echo $TEMPLINE | awk -F"@@" '{print $2}'`
        REPLACETEXT="@@${MACRO}@@"

        SETTING=$(get_setting_value $TEMPLATE_FILE $MACRO)

        if [ "$SETTING" != "" ]; then
            NEWSETTING=`echo $SETTING | sed 's#\/#\\\/#g'`
            TEMPLINE=`echo $TEMPLINE | sed "s/$REPLACETEXT/$NEWSETTING/g"`
        fi  
    done

    echo $TEMPLINE
}

function replace_macros()
{
    CURRENT_TEMPLATE=$1
    echo "CURRENT_TEMPLATE is $CURRENT_TEMPLATE"
    FILENAME=$TEMPLATE_DIR/$1
    #echo "FILENAME is $FILENAME"
    NEWFILE=${FILENAME}.new
    BAKFILE=${FILENAME}.bak
    #echo "NEWFILE is $NEWFILE"
    remove_bakfile $NEWFILE
    cp ${FILENAME} ${BAKFILE}

    #echo "" >> $BAKFILE

    while read -r LINE || [ -n "$LINE" ]
    do
        echo ${LINE}
        case "$LINE" in *@@*@@*)
            NEWLINE=$(replace_macro $CURRENT_TEMPLATE "$LINE")
            if [ "$NEWLINE" = "" ]; then
                echo "*****************************************************"
                echo "WARNING: THE MACRO IN LINE $LINE IS NOT FOUND, please fix the issue and rerun the script"
                echo "*****************************************************"
            else
                echo "$NEWLINE" >> $NEWFILE
                echo "$NEWLINE"
			fi
            ;;
            *) 
            echo "$LINE" >> $NEWFILE
            ;;
            esac
    done  < $BAKFILE

    rm $BAKFILE
    FINALNAME=`basename $FILENAME`
    #echo "FINALNAME is $FINALNAME, NEWFILE is $NEWFILE"
    save_to_recipe $NEWFILE $FINALNAME
}

function save_to_recipe(){
    TEMPNAME=$1
    FINALNAME=$2

    FILENAME=${RECIPE_DIR}/$FINALNAME
    mv $TEMPNAME $FILENAME	
}

function remove_bakfile()
{
    FILE=$1
    if [ -e $FILE ]; then
        rm $FILE
        echo "remove file $FILE"
    fi
}

if [ $# -lt 1 -o $# -gt 3 ]; then
echo "MISSING PARAMETER\nThe correct usage is: ./generate_conf.sh <env> <role> [particle or yidian]"
exit 1
fi

mkdir -p $RECIPE_DIR
rm -f $RECIPE_DIR/*
get_files $TEMPLATE_DIR

for TEMPLATE_FILE in ${ARRAY[@]}; do
    echo "replacing macro for template file $TEMPLATE_FILE ...."
    replace_macros $TEMPLATE_FILE
done

cp ${SCRIPTPATH}/recipe/*.php ${SCRIPTPATH}/../
