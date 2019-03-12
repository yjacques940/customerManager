FROM microsoft/dotnet:2.2-aspnetcore-runtime AS base
WORKDIR /app
EXPOSE 80
EXPOSE 443

FROM microsoft/dotnet:2.2-sdk AS build
WORKDIR /src
COPY ["CustomerManagerWebApi/CustomerManagerWebApi.csproj", "CustomerManagerWebApi/"]
RUN dotnet restore "CustomerManagerWebApi/CustomerManagerWebApi.csproj"
COPY . .
WORKDIR "/src/CustomerManagerWebApi"
RUN dotnet build "CustomerManagerWebApi.csproj" -c Release -o /app

FROM build AS publish
RUN dotnet publish "CustomerManagerWebApi.csproj" -c Release -o /app

FROM base AS final
WORKDIR /app
COPY --from=publish /app .
ENTRYPOINT ["dotnet", "CustomerManagerWebApi.dll"]