using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.Extensions.Configuration;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class JobController : BaseController<JobService,Appointment>
    {
        public IConfiguration Configuration { get; }
        public AppointmentService AppointmentService { get; }
        public PhoneNumberService PhoneService { get; }

        public JobController(JobService service,IConfiguration configuration, AppointmentService appointmentService, PhoneNumberService phoneService) : base(service)
        {
            Configuration = configuration;
            AppointmentService = appointmentService;
            PhoneService = phoneService;
        }

        [HttpGet, Route("NewAppointments")]
        public bool SendNewAppointmentsToEmployees()
        {
            return Service.SendNewAppointments(Configuration,AppointmentService,PhoneService);
        }
    }
}
