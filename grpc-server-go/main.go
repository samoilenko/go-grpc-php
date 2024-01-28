package main

import (
	"context"
	"fmt"
	userGrpc "grpc-server/protos"
	"log"
	"net"

	"google.golang.org/grpc"
	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/status"
)

type UserService struct {
	userGrpc.UnsafeUserServer
}

func (u *UserService) FindById(ctx context.Context, req *userGrpc.FindByIdRequest) (*userGrpc.FindByIdReply, error) {
	if req.GetId() == "some-unique-id" {
		return &userGrpc.FindByIdReply{Id: "some-unique-id", Name: "Lobster"}, nil
	}

	return nil, status.Error(codes.NotFound, fmt.Sprintf("user '%s' was not found", req.GetId()))
}

func (u *UserService) mustEmbedUnimplementedUserServer() {}

func LoggingInterceptor(ctx context.Context, req interface{}, info *grpc.UnaryServerInfo, handler grpc.UnaryHandler) (interface{}, error) {
	log.Printf("Received request for %s", info.FullMethod)

	// Call the actual handler to process the request
	resp, err := handler(ctx, req)

	// Log the response
	log.Printf("Sent response for %s", info.FullMethod)

	return resp, err
}

func main() {
	userService := &UserService{}
	server := grpc.NewServer(grpc.UnaryInterceptor(LoggingInterceptor))

	userGrpc.RegisterUserServer(
		server,
		userService,
	)

	lis, err := net.Listen("tcp", ":9090")
	err = server.Serve(lis)
	if err != nil {
		fmt.Println(err)
	}
}
