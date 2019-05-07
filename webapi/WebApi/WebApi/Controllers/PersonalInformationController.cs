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

        [HttpPost, Route("UpdatePersonalInformation")]
        public ActionResult UpdatePersonalInformation([FromBody] UserUpdatePersonalInformation personalInformation)
        {
            if (personalInformation != null)
                return Ok(Service.UpdatePersonalInformation(personalInformation));

            return BadRequest();
        }

        [HttpGet, Route("GetPersonalInformationWithCustomerId/{idCustomer}")]
        public ActionResult<RegistrationInformation> GetPersonalInformationWithCustomerId(int idCustomer)
        {
            var user = Service.GetPersonalInformationWithCustomerId(idCustomer);
            if (user == null)
                return BadRequest();

            return user;
        }

        [HttpPost, Route("UpdatePersonalInformationWithCustomerId")]
        public ActionResult UpdatePersonalInformationWithCustomerId([FromBody] UpdateCustomerPersonalInformation personalInformation)
        {
            if (personalInformation != null)
                return Ok(Service.UpdatePersonalInformationWithCustomerId(personalInformation));

            return BadRequest();
        }
    }
}
