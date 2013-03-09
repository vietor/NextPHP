# Makefile for NextPHP

TARGET=NextPHP

.PHONY: all bin doc clean

all: bin doc

bin:
	php tools/compress.php ./system $(TARGET).php

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
