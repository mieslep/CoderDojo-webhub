#!/bin/bash -x

if [ -z "$1" -o -z "$2" -o -z "$3" ]; then
  echo "usage error"
  exit 1
fi

username=$1
realname=$2
homedir=$3

if [ $(uname -s) == "Darwin" ]; then
 os=OSX
else
 os=LINUX
fi


function getUID() {
  case $os in 
    OSX) currentMaxUniqueID=$(sudo dscl . -list /Users UniqueID | awk '{print $2}' | sort -ug | tail -1)
         ;;
    LINUX) 
         currentMaxUniqueID=$(cut -d: -f3 /etc/passwd | egrep -v '65534' | sort -ug) 
         ;;
   esac
  
   if [[ $currentMaxUniqueID -lt 8000 ]]; then
     rtn=8000
   else
     rtn=$(($currentMaxUniqueID + 1))
   fi
   echo $rtn
}

newUID=$(getUID)

case $os in
  OSX) echo newUID=$newUID

       thisUID=$(sudo dscl . -read /Users/$username UniqueID 2>/dev/null)
       if [ -z "$thisUID" ]; then
         thisUID=$newUID
         sudo dscl . -create /Users/$username
         sudo dscl . -create /Users/$username UserShell /usr/bin/false
         sudo dscl . -create /Users/$username RealName "$realname"
         sudo dscl . -create /Users/$username UniqueID $thisUID
#         sudo dscl . -create /Users/$username PrimaryGroupID 20
         sudo dscl . -create /Users/$username NFSHomeDirectory $homedir
       fi

       ;;
  LINUX)
       echo newUID=$newUID
       ;;
esac


#
#if [ $(uname -s) == "Drwin" ]; then
#  currentMaxUniqueID=$(sudo dscl . -list /Users UniqueID | awk '{print $2}' | sort -ug | tail -1)
#
#  sudo dscl . -create /Users/$username UserShell /usr/bin/false
#  sudo dscl . -create /Users/$username RealName "$realname"
#  sudo dscl . -create /Users/$username UniqueID 8005
#  sudo dscl . -create /Users/$username PrimaryGroupID 20
#  sudo dscl . -create /Users/$username NFSHomeDirectory $homedir
#else
#  echo "Linux"
#fi


