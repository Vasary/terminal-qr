build:
	docker build . --file build/Dockerfile.develop --tag vasary/qr_terminal:develop

run:
	docker run -it -v $(PWD):/app vasary/qr_terminal:develop sh
