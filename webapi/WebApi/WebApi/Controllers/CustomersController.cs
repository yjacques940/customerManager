using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
using System.Linq;
using Microsoft.Extensions.Configuration;
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
        public IConfiguration Configuration { get; }
        public UserService UserService { get; }

        public CustomersController(WebApiContext context, CustomerService service,IConfiguration configuration,UserService userService) : base(service)
        {
            Configuration = configuration;
            UserService = userService;
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

        [HttpGet, Route("FullName/{customerId}")]
        public ActionResult GetCustomerFullName(int customerId)
        {
            return Ok(Service.GetCustomerFullName(customerId));
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

        [HttpGet, Route("AllCustomerInfo/{customerId}")]
        public ActionResult<AllCustomerInformation> GetAllCustomerInformation(int customerId)
        {
            var customerInfo = Service.GetAllCustomerInformationByCustomerId(customerId);
            if (customerInfo == null)
                return NoContent();

            return Ok(customerInfo);
        }

        [HttpGet, Route("CustomerIdByUserId")]
        public ActionResult<int> GetCustomerIdByUserId([FromQuery] int userId)
        {
            var user = Service.GetCustomerIdByUserId(userId);
            if (user != null)
                return Ok(user.IdCustomer);

            return NotFound();
        }

        [HttpPost, Route("CreateUser")]
        public ActionResult CreateUserForACustomer([FromBody] EmailAndCustomerInfo emailInfo)
        {
            if(Service.CreateUserForCustomer(emailInfo,Configuration,UserService) != 0)
                return Ok();

            return NoContent();
        }
    }
}
