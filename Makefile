# Makefile for NextPHP

TARGET=NextPHP

all: doc

doc:
	doxygen
	$(MAKE) -C latex
	cp latex/refman.pdf $(TARGET).pdf
	tar -czf $(TARGET)-html.tar.gz html

clean:
	rm -fr html latex
	rm -f *.log
	rm -f $(TARGET).pdf
	rm -f $(TARGET)-html.tar.gz
