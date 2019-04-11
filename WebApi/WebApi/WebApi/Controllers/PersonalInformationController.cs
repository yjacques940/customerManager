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
    public class PersonalInformationController : BaseController<PersonalInformationService, Customer>
    {
        public PersonalInformationController(PersonalInformationService service) : base(service)
        {
        }

        [HttpGet, Route("PersonalInformation/{idUser}")]
        public ActionResult<RegistrationInformation> GetUserInformation(int idUser)
        {
            var user = Service.GetRegistrationInformation(idUser);
            if (user == null)
                return BadRequest();

            return user;
        }
    }
}
