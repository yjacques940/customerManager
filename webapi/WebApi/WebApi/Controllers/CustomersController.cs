using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class CustomersController : BaseController<CustomerService, Customer>
    {
        public CustomersController(WebApiContext context, CustomerService service) : base(service)
        {
        }

        [HttpGet, Route("GetAll")]
        public ActionResult<IEnumerable<Customer>> GetCustomer()
        {
            return Service.GetList();
        }

        [HttpGet, Route("ByUserId/{idUser}")]
        public ActionResult<Customer> GetCustomerByUserId(int idUser)
        {
            return Ok(Service.GetCustomerByUserId(idUser));
        }

        [HttpGet, Route("FullName/{id}")]
        public ActionResult GetCustomerFullName(int id)
        {
            return Ok(Service.GetCustomerFullName(id));
        }

        [HttpPut, Route("AddNew/{customer}")]
        public ActionResult AddNewCustomerObject(Customer customer)
        {
            return Ok(Service.AddNewCustomer(customer));
        }

        [HttpPut, Route("UpdateCustomerLastName/{id}/{lastName}")]
        public ActionResult UpdateCustomerLastName(int id, string lastName)
        {
            return Ok(Service.ChangeCustomerLastName(id, lastName));
        }

        [HttpDelete, Route("Delete/{id}")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public ActionResult DeleteCustomer(int id)
        {
            if (Service.Remove(id))
                return NoContent();

            return BadRequest();
        }

        [HttpPost, Route("GetCustomersByPhone")]
        public ActionResult GetCustomersByPhone([FromBody]string phone)
        {
            return Ok(Service.GetCustomersByPhone(phone));
        }

        [HttpPost, Route("GetCustomersByName")]
        public ActionResult GetCustomersByName([FromBody]string name)
        {
            return Ok(Service.GetCustomersByName(name));
        }

        [HttpGet, Route("CustomersWithPhoneInfo")]
        public ActionResult<IEnumerable<CustomerAndPhoneNumberInformation>> GetCustomersWithPhoneInformation()
        {
            return Ok(Service.GetCustomersWithPhone());
        }

        [HttpPost, Route("GetCustomerFollowUps")]
        public ActionResult GetCustomerAppointmentsAndFollowUps([FromBody] int customerId)
        {
            if (customerId != 0)
                return Ok(Service.GetCustomerFollowUps(customerId));

            return BadRequest();
        }
    }
}
