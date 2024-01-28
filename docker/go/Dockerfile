FROM golang:bookworm

ENV PATH="$PATH:$(go env GOPATH)/bin"

COPY grpc-server-go/ /app/grpc-server-go/
COPY protos/ /app/protos/

# install protobuf and go grpc plugin
RUN apt-get update && apt install -y protobuf-compiler
RUN go install google.golang.org/protobuf/cmd/protoc-gen-go@v1.28 && \
    go install google.golang.org/grpc/cmd/protoc-gen-go-grpc@v1.2

# compile userService.proto file
RUN cd /app && protoc --go_out=grpc-server-go \
    --go_opt=paths=source_relative \
    --go-grpc_out=grpc-server-go \
    --go-grpc_opt=paths=source_relative \
    protos/userService.proto


RUN cd /app/grpc-server-go \
    # install modules
    && go mod init grpc-server && go mod tidy \
    # compile app
    && go build -o grpc-server-app .

WORKDIR /app/grpc-server-go

# CMD ["tail",  "-f", "/dev/null"]

CMD ["./grpc-server-app"]