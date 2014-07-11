#!/bin/bash

if [[ $# == 0 ]]; then
  echo "Usage: $0 TAG"
  exit;
fi

tag=$1
version="querypath-$1"
master='../../../QueryPath'

#mkdir -p ./build/$version
cd ./build/
git clone $master ./$version
cd $version
git checkout $tag
cd ./docs
./gendocs.sh
cd ../../
tar --exclude .git -zcf "$version.tgz" $version
zip -r $version.zip $version -x "*.git/*"
mv $version.tgz ../
mv $version.zip ../

echo "Ready to clean up"
#rm -rf $version
