iostool
=======

Sorting import header for ObjC file (.h and .m)

A small utility to help you sort your #import.

To use it:

Assume that your code dir is at ~/foo/bar/* and source file of main.php and sortHeader.php is at ~/iostool/*

1) Backup your file first. Commit any change before proceed to step 2.

2) Run "php ~/iostool/main.php ~/foo/bar" in command line.

3) Build and run your app to make sure that nothing break.


The sortHeader file would look for file match with postfix "*.h" and "*.m" and sort its header.

To run an example, do php ~/iostool/main.php ~/iostool/testData/
