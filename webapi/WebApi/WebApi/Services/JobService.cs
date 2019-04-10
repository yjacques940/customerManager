using Microsoft.Extensions.Configuration;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Data;
using WebApi.Models;
using WebApi.Validators;

namespace WebApi.Services
{
    public class JobService : BaseCrudService<Appointment>
    {
        public JobService(IWebApiContext context) : base(context)
        {
        }

        public bool SendNewAppointments(IConfiguration configuration, AppointmentService appointmentService, PhoneNumberService phoneService)
        {
           var appointments = appointmentService.GetNewAppointments(phoneService);
            return EmailSender.SendNewAppointmentsToEmployees(appointments,configuration);
        }
    }
}
