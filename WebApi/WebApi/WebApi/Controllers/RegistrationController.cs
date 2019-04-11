using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class RegistrationController : BaseController<RegistrationService, Customer>
    {
        public RegistrationController(RegistrationService service) : base(service)
        {
        }

        [HttpPost, Route("Register")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public ActionResult RegisterNewUser([FromBody]RegistrationInformation registrationInformation)
        {
            if (registrationInformation == null)
                return BadRequest();
            return Ok(Service.RegisterNewUser(registrationInformation) == null);
        }
    }
}
