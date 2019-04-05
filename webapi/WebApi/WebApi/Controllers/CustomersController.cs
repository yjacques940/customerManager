using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
using WebApi.Data;
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

        [HttpGet,Route("GetAll")]
        public ActionResult<IEnumerable<Customer>> GetCustomer()
        {
            return Service.GetList();
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

        [HttpDelete,Route("Delete/{id}")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public ActionResult DeleteCustomer(int id)
        {
            if (Service.Remove(id))
                return NoContent();

            return BadRequest();
        }

        [HttpPost, Route("UpdateCustomerEmail/{id}/{email}")]
        public ActionResult UpdateCustomerEmail(int id, string email)
        {
            return Ok(Service.ChangeCustomerEmail(id, email));
        }
    }
}
