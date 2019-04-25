using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Configuration;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class JobsController : BaseController<JobService, ActionToken>
    {
        private readonly AppointmentService appointmentService;
        private readonly IConfiguration configuration;

        public JobsController(JobService service, AppointmentService appointmentService) : base(service)
        {
            this.appointmentService = appointmentService;
        }

        [HttpGet, Route("Daily")]
        public ActionResult RunDailyJobs()
        {
            Service.SendConfirmationEmailToUsers(configuration, appointmentService);
            return Ok();
        }
    }
}
