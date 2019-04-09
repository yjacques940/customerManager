namespace WebApi.Models
{
    public class PhoneNumber : BaseModel
    {
        public string Phone { get; set; }
        public string Extension { get; set; }
        public int IdPhoneType { get; set; }
        public int IdCustomer { get; set; }
    }
}
