clean:
	rm -rf build

build:
	mkdir build
	ppm --compile="src/acm" --directory="build"

install:
	ppm --no-prompt --install="build/net.intellivoid.acm.ppm"