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

        public JobsController(JobService service, AppointmentService appointmentService, IConfiguration configuration) : base(service)
        {
            this.appointmentService = appointmentService;
            this.configuration = configuration;
        }

        [HttpGet, Route("Daily")]
        public ActionResult RunDailyJobs()
        {
            Service.SendAskConfirmationEmailToUsers(configuration, appointmentService);
            return Ok();
        }
    }
}
