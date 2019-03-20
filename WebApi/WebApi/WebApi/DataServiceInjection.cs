using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.Extensions.DependencyInjection;
using WebApi.Services;

namespace WebApi
{
    public static class DataServiceInjection
    {
        public static IServiceCollection InjectDataServices(this IServiceCollection services)
        {
            services.AddTransient<CustomerService>();
            return services;
        }
    }
}
