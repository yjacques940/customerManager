using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.Extensions.Configuration;
using WebApi.Validators;
using WebApi.DTO;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class EmailController : Controller
    {
        private readonly IConfiguration configuration;

        public EmailController(IConfiguration configuration)
        {
            this.configuration = configuration;
        }

        [HttpPost]
        public ActionResult SendEmailToAdmins([FromBody]Message message)
        {
            if (EmailSender.SendEmail(message.MessageContent, configuration))
                return Ok();

            return BadRequest();
        }
    }
}
