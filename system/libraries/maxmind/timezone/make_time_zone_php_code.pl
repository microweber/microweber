#!/usr/bin/perl

use strict;

my $old_country;
my $old_region;

# Obtain timezone.txt from http://www.maxmind.com/timezone.txt
open(FILE,"timezone.txt");
my $str = <FILE>;
print "<?php \n";
print "function get_time_zone(\$country,\$region) {\n";
print "  switch (\$country) { \n";

while ($str = <FILE>) {
  $str =~ s!\s*$!!; 
  my ($country,$region,$timezone) = split("\t",$str);
  #print $country . "," . $region . "," . $timezone . "\n";
  if ($country ne $old_country) {
    if ($old_region ne "") {
      print "  } \n";
      print "  break; \n";
    }
    print "case " . qq(") . $country . qq(") . ":\n";
    if ($region ne "") {
      print "    switch (\$region) { \n";
    }
  }
  if ($region ne "") {
    print "  case " . qq(") . $region . qq(") . ":\n  ";
  }
  print qq(    \$timezone = ") . $timezone . qq(") . ";\n";
  if ($region ne "") {
    print "      break; \n";
  } else {
    print "    break; \n";
  }
  $old_country = $country;
  $old_region = $region;
}
print "  } \n";
print "  return \$timezone; \n";

print "} \n";

print "?> \n";

close(FILE);
