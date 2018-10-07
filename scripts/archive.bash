#!/usr/bin/env bash

[[ "$DEBUG" = "true" ]] && set -x
set -euo pipefail

current_script_dir() {
  local source="${BASH_SOURCE[0]}"

  # resolve $source until the file is no longer a symlink
  if type -p readlink >/dev/null; then
    while [ -h "$source" ]; do
      local dir="$( cd -P "$( dirname "$source" )" >/dev/null && pwd )"
      local source="$(readlink "$source")"

      # if $source was a relative symlink,
      # we need to resolve it relative to the path where the symlink file was located
      if [[ "$source" != /* ]]; then
        local source="$dir/$source"
      fi
    done
  fi

  local dir="$( cd -P "$( dirname "$source" )" >/dev/null && pwd )"
  echo $dir
}

SCRIPT_DIR="$(current_script_dir)"

if ! command -v tar >/dev/null 2>&1; then
    echo "Require 'tar' command" >&2
    exit 1
fi

TARGET_DIR="$(dirname "$SCRIPT_DIR")"

TARGET_FILES=$(cat <<EOS
conf
lang
LICENSE
plugin.info.txt
syntax.php
EOS
)

tar \
  -C $TARGET_DIR \
  -zcvf archive.tar.gz \
  $TARGET_FILES
