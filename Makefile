# Makefile for NextPHP

TARGET=NextPHP

all: doc

doc:
	doxygen tools/Doxyfile
	$(MAKE) -C latex
	cp latex/refman.pdf $(TARGET).pdf
	tar -czf $(TARGET)-html.tar.gz html
	php tools/compress.php ./system $(TARGET).php

clean:
	rm -fr html latex
	rm -f *.log
	rm -f $(TARGET).php
	rm -f $(TARGET).pdf
	rm -f $(TARGET)-html.tar.gz
