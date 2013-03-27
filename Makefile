# Makefile for NextPHP

TARGET=NextPHP

.PHONY: all bin doc clean

all: bin doc

bin-core:
	php tools/compress.php $(TARGET).php ./system/core ./system/libs

bin:
	php tools/compress.php $(TARGET).php ./system

doc:
	doxygen tools/Doxyfile
	$(MAKE) -C latex
	cp latex/refman.pdf $(TARGET).pdf
	tar -czf $(TARGET)-html.tar.gz html

clean:
	rm -fr html latex
	rm -f *.log
	rm -f $(TARGET).php
	rm -f $(TARGET).pdf
	rm -f $(TARGET)-html.tar.gz
