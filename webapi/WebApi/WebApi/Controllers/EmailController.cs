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
        private readonly UserService userService;

        public EmailController(IConfiguration configuration,UserService userService)
        {
            this.configuration = configuration;
            this.userService = userService;
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
            if (userService.SendChangePasswordEmail(configuration,email.Email,false))
                return Ok();

            return BadRequest();
        }
    }
}
