#!/usr/bin/env bash
#   Use this script to test if a given TCP host/port are available

WAITFORIT_cmdname=$(basename $0)
WAITFORIT_timeout=15
WAITFORIT_quiet=0

echoerr() { if [ "$WAITFORIT_quiet" -eq 0 ]; then echo "$@" 1>&2; fi }

wait_for()
{
    if [ "$WAITFORIT_timeout" -gt 0 ]; then
        echoerr "$WAITFORIT_cmdname: waiting $WAITFORIT_timeout seconds for $WAITFORIT_host:$WAITFORIT_port"
    else
        echoerr "$WAITFORIT_cmdname: waiting for $WAITFORIT_host:$WAITFORIT_port without a timeout"
    fi
    WAITFORIT_start_ts=$(date +%s)
    while :
    do
        (echo > /dev/tcp/$WAITFORIT_host/$WAITFORIT_port) >/dev/null 2>&1
        WAITFORIT_result=$?
        if [ $WAITFORIT_result -eq 0 ]; then
            WAITFORIT_end_ts=$(date +%s)
            echoerr "$WAITFORIT_cmdname: $WAITFORIT_host:$WAITFORIT_port is available after $((WAITFORIT_end_ts - WAITFORIT_start_ts)) seconds"
            break
        fi
        sleep 1
    done
    return $WAITFORIT_result
}

# Usage info
show_usage()
{
  echo "Usage: $WAITFORIT_cmdname host:port [-q] [-t timeout]"
  echo " -s  Silent mode"
  echo " -t  Timeout in seconds, zero for no timeout"
  echo " -h  Show this help"
  exit 1
}

# Parse script arguments
while getopts "qhs:t" option
do
  case "${option}" in
    s)
      WAITFORIT_quiet=1
      ;;
    t)
      WAITFORIT_timeout=${OPTARG}
      ;;
    h)
      show_usage
      ;;
    *)
      show_usage
      ;;
  esac
done

# Check if there are enough arguments
if [ $# -eq 0 ]; then
  show_usage
fi

WAITFORIT_hostport=(${1//:/ })
WAITFORIT_host=${WAITFORIT_hostport[0]}
WAITFORIT_port=${WAITFORIT_hostport[1]}

wait_for

