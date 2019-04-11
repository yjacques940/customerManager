using Microsoft.Extensions.DependencyInjection;
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
            services.AddTransient<UserService>();
            services.AddTransient<PhoneTypeService>();
            services.AddTransient<PermissionService>();
            services.AddTransient<RegistrationService>();
            services.AddTransient<PersonalInformationService>();
            services.AddTransient<StateService>();
            return services;
        }
    }
}
