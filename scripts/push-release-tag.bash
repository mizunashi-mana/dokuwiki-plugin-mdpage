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

if [ "$#" -lt 1 ] || [[ "$1" != [0-9]*.[0-9]*.[0-9]* ]]; then
    echo "Usage: $0 VERSION([0-9]+.[0-9]+.[0-9]+)" >&2
    exit 1
fi

if ! command -v git >/dev/null 2>&1; then
    echo "Require 'git' command" >&2
    exit 1
fi

TARGET_DIR="$(dirname "$SCRIPT_DIR")"
if ! [ -d "$TARGET_DIR/.git" ]; then
    echo "Not git repository" >&2
    exit 1
fi

if [ "$(git rev-parse --abbrev-ref HEAD)" != "master" ]; then
    echo "Must checkout master branch." >&2
    exit 1
fi

if [ -z "${FORCE_RELEASE:-}" ]; then
    PLUGIN_DATE="$(git -C $TARGET_DIR show master:plugin.info.txt | grep 'date' | awk '{print $2}')"
    if [ "$PLUGIN_DATE" != "$(date +'%Y-%m-%d')" ]; then
        cat >&2 <<EOS
You may forget to update date on plugin.info.txt.
If you are ok, please rerun with FORCE_RELEASE environment.
EOS
        exit 1
    fi
fi

git fetch origin master
if [ -z "${FORCE_RELEASE:-}" ]; then
    if [ "$(git describe origin/master)" != "$(git describe master)" ]; then
        cat >&2 <<EOS
You may forget to pull master changes.
If you are ok, please rerun with FORCE_RELEASE environment.
EOS
        exit 1
    fi
fi

VERSION="$1"
git tag -a "v$VERSION" -m "Version $VERSION"
git push origin "v$VERSION"

cat <<EOS
Success to release: https://github.com/mizunashi-mana/dokuwiki-plugin-mdpage/releases/tag/v$VERSION

Do not forget updating https://www.dokuwiki.org/plugin:mdpage !
EOS
