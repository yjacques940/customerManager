﻿using Microsoft.Extensions.DependencyInjection;
using WebApi.Services;

namespace WebApi
{
    public static class DataServiceInjection
    {
        public static IServiceCollection InjectDataServices(this IServiceCollection services)
        {
            services.AddTransient<CustomerService>();
            services.AddTransient<AppointmentService>();
            services.AddTransient<PhoneNumberService>();
            services.AddTransient<CustomerPhoneNumberService>();
            services.AddTransient<UserService>();
            return services;
        }
    }
}