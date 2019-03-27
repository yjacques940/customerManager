using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.Extensions.Configuration;
using WebApi.Validators;

namespace WebApi.Controllers
{
    public class EmailController : Controller
    {
        private readonly IConfiguration configuration;

        public EmailController(IConfiguration configuration)
        {
            this.configuration = configuration;
        }

        [HttpPost, Route("{message}")]
        public void SendEmailToAdmins(string message)
        {
             EmailSender.SendEmail(message,configuration);
        }
    }
}
