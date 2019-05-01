using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.Extensions.Configuration;
using WebApi.Validators;
using WebApi.DTO;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class EmailController : Controller
    {
        private readonly IConfiguration configuration;
        private readonly JobService jobService;

        public EmailController(IConfiguration configuration,JobService jobService)
        {
            this.configuration = configuration;
            this.jobService = jobService;
        }

        [HttpPost]
        public ActionResult SendEmailToAdmins([FromBody]Message message)
        {
            if (EmailSender.SendEmail(message.MessageContent, configuration))
                return Ok();

            return BadRequest();
        }

        [HttpPost, Route("ChangePassword")]
        public ActionResult SendChangePasswordEmail([FromBody] EmailAddress email)
        {
            if (jobService.SendChangePasswordEmail(configuration,email.Email))
                return Ok();

            return BadRequest();
        }
    }
}
