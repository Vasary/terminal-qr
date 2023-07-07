build:
	docker build . --file build/Dockerfile.develop --tag vasary/qr_terminal:develop

run:
	docker run -it -v $(PWD):/app -p 8080:8080 vasary/qr_terminal:develop sh

prod:
	docker build . --file build/Dockerfile --tag vasary/qr_terminal:production

run_prod:
	docker run -p 81:80 -e APP_SECRET=c4ca4238a0b923820dcc509a6f75849b vasary/qr_terminal:production
