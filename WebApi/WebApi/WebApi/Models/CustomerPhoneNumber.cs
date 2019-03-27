namespace WebApi.Models
{
    public class CustomerPhoneNumber : BaseModel
    {
        public int IdPhoneNumber { get; set; }
        public int IdCustomer { get; set; }
    }
}
