/*                                                                -*- C -*-
   +----------------------------------------------------------------------+
   | PHP Version 5                                                        |
   +----------------------------------------------------------------------+
   | Copyright (c) 1997-2007 The PHP Group                                |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.php.net/license/3_01.txt                                  |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
   | Author: Stig Sæther Bakken <ssb@php.net>                             |
   +----------------------------------------------------------------------+
*/

/* $Id$ */

#define CONFIGURE_COMMAND " './configure'  '--enable-inline-optimization' '--prefix=/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5' '--exec-prefix=/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5' '--with-curl=/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5' '--with-zlib=/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5' '--with-zlib-dir=/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5' '--with-yaml=/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5' '--with-mcrypt=/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5' '--with-gmp=/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5' '--without-ncurses' '--without-readline' '--enable-xdebug=shared' '--enable-mbstring' '--enable-calendar' '--enable-weakref' '--enable-pthreads' '--disable-fileinfo' '--disable-libxml' '--disable-xml' '--disable-dom' '--disable-simplexml' '--disable-xmlreader' '--disable-xmlwriter' '--disable-cgi' '--disable-session' '--disable-debug' '--disable-pdo' '--without-pear' '--without-iconv' '--without-pdo-sqlite' '--with-pic' '--enable-phar' '--enable-ctype' '--enable-sockets' '--enable-shared=no' '--enable-static=yes' '--enable-shmop' '--enable-maintainer-zts' '--disable-short-tags' '--enable-zend-signals' '--enable-pcntl' '--enable-embedded-mysqli' '--enable-mysqlnd' '--with-mysqli=mysqlnd' '--enable-bcmath' '--enable-cli' '--enable-zip' '--enable-ftp' '--with-zend-vm=GOTO' 'CC=gcc' 'CFLAGS=-s '-O2' '-fPIC' '-O2' '-DSQLITE_HAVE_ISNAN' '-fno-math-errno' '-funsafe-math-optimizations' '-fno-signed-zeros' '-fno-trapping-math' '-ffinite-math-only' '-fno-rounding-math' '-fno-signaling-nans' '-ftree-vectorize' '-fomit-frame-pointer' '-funswitch-loops' '-fivopts' '-m64' '-arch' '-fomit-frame-pointer' '-mmacosx-version-min=10.5' '-Qunused-arguments' '-Wno-error=unused-command-line-argument-hard-error-in-future'' 'LDFLAGS= '-Wl,-rpath,@loader_path/../lib'' 'CXX=g++' 'CXXFLAGS=-s '-O2' '-fPIC' '-O2' '-DSQLITE_HAVE_ISNAN' '-fno-math-errno' '-funsafe-math-optimizations' '-fno-signed-zeros' '-fno-trapping-math' '-ffinite-math-only' '-fno-rounding-math' '-fno-signaling-nans' '-ftree-vectorize' '-fomit-frame-pointer' '-funswitch-loops' '-fivopts' '-m64' '-arch' '-fomit-frame-pointer' '-mmacosx-version-min=10.5' '-Qunused-arguments' '-Wno-error=unused-command-line-argument-hard-error-in-future''"
#define PHP_ADA_INCLUDE		""
#define PHP_ADA_LFLAGS		""
#define PHP_ADA_LIBS		""
#define PHP_APACHE_INCLUDE	""
#define PHP_APACHE_TARGET	""
#define PHP_FHTTPD_INCLUDE      ""
#define PHP_FHTTPD_LIB          ""
#define PHP_FHTTPD_TARGET       ""
#define PHP_CFLAGS		"$(CFLAGS_CLEAN) -prefer-non-pic -static"
#define PHP_DBASE_LIB		""
#define PHP_BUILD_DEBUG		""
#define PHP_GDBM_INCLUDE	""
#define PHP_IBASE_INCLUDE	""
#define PHP_IBASE_LFLAGS	""
#define PHP_IBASE_LIBS		""
#define PHP_IFX_INCLUDE		""
#define PHP_IFX_LFLAGS		""
#define PHP_IFX_LIBS		""
#define PHP_INSTALL_IT		""
#define PHP_IODBC_INCLUDE	""
#define PHP_IODBC_LFLAGS	""
#define PHP_IODBC_LIBS		""
#define PHP_MSQL_INCLUDE	""
#define PHP_MSQL_LFLAGS		""
#define PHP_MSQL_LIBS		""
#define PHP_MYSQL_INCLUDE	""
#define PHP_MYSQL_LIBS		""
#define PHP_MYSQL_TYPE		""
#define PHP_ODBC_INCLUDE	""
#define PHP_ODBC_LFLAGS		""
#define PHP_ODBC_LIBS		""
#define PHP_ODBC_TYPE		""
#define PHP_OCI8_SHARED_LIBADD 	""
#define PHP_OCI8_DIR			""
#define PHP_OCI8_ORACLE_VERSION		""
#define PHP_ORACLE_SHARED_LIBADD 	"@ORACLE_SHARED_LIBADD@"
#define PHP_ORACLE_DIR				"@ORACLE_DIR@"
#define PHP_ORACLE_VERSION			"@ORACLE_VERSION@"
#define PHP_PGSQL_INCLUDE	""
#define PHP_PGSQL_LFLAGS	""
#define PHP_PGSQL_LIBS		""
#define PHP_PROG_SENDMAIL	"/usr/sbin/sendmail"
#define PHP_SOLID_INCLUDE	""
#define PHP_SOLID_LIBS		""
#define PHP_EMPRESS_INCLUDE	""
#define PHP_EMPRESS_LIBS	""
#define PHP_SYBASE_INCLUDE	""
#define PHP_SYBASE_LFLAGS	""
#define PHP_SYBASE_LIBS		""
#define PHP_DBM_TYPE		""
#define PHP_DBM_LIB		""
#define PHP_LDAP_LFLAGS		""
#define PHP_LDAP_INCLUDE	""
#define PHP_LDAP_LIBS		""
#define PHP_BIRDSTEP_INCLUDE     ""
#define PHP_BIRDSTEP_LIBS        ""
#define PEAR_INSTALLDIR         ""
#define PHP_INCLUDE_PATH	".:"
#define PHP_EXTENSION_DIR       "/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5/lib/php/extensions/no-debug-zts-20131226"
#define PHP_PREFIX              "/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5"
#define PHP_BINDIR              "/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5/bin"
#define PHP_SBINDIR             "/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5/sbin"
#define PHP_MANDIR              "/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5/php/man"
#define PHP_LIBDIR              "/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5/lib/php"
#define PHP_DATADIR             "${prefix}/share"
#define PHP_SYSCONFDIR          "/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5/etc"
#define PHP_LOCALSTATEDIR       "/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5/var"
#define PHP_CONFIG_FILE_PATH    "/Users/public-demo/.jenkins/jobs/PHP-PocketMine-MacOS/workspace/compile/mac64/bin/php5/lib"
#define PHP_CONFIG_FILE_SCAN_DIR    ""
#define PHP_SHLIB_SUFFIX        "so"
